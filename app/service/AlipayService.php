<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/3
 * Time: 12:27
 */

namespace service;

use atphp\Config;
use atphp\util\HttpUtil;
use atphp\util\LogUtil;

/**
 * 支付宝自定义支付方式,目前只支持web 支付,如果想要别的方式的话,之后再封装
 * Class AlipayService
 * @package pay\service
 */
class AlipayService
{
    /**
     * 支付宝web支付
     * @param array $request
     */
    public function webPay(array $request)
    {
        $data = array(
            'app_id' => Config::get("alipay")['app_id'],
            'version' => '1.0',
            'format' => 'JSON',
            'sign_type' => 'RSA2',
            'method' => 'alipay.trade.wap.pay',
            'timestamp' => date('Y-m-d H:i:s'),
            'alipay_sdk' => 'alipay-sdk-php-20161101',
            'notify_url' => Config::get('alipay')['notify_url'],
            'return_url' => Config::get('alipay')['return_url'],
            'charset' => 'UTF-8',
        );

        $data ['biz_content'] = json_encode($request, JSON_UNESCAPED_UNICODE);


        $data["sign"] = $this->generateSign($data, $data['sign_type']);
        echo $this->buildRequestForm($data);
    }

    /**
     * 支付宝扫码
     * @param array $request
     * @return mixed
     */
    public function qrcodePay(array $request)
    {
        $data = array(
            'app_id' => Config::get("alipay")['app_id'],
            'method' => 'alipay.trade.precreate',
            'format' => 'JSON',
            'charset' => 'UTF-8',
            'sign_type' => 'RSA2',
            'version' => '1.0',
            'timestamp' => date('Y-m-d H:i:s'),
            'notify_url' => Config::get('alipay')['notify_url'],
        );

        $data ['biz_content'] = json_encode($request, JSON_UNESCAPED_UNICODE);

        $data["sign"] = $this->generateSign($data, $data['sign_type']);


        $response = HttpUtil::curl(Config::get('alipay')['gatewayUrl'], $data);
        $response_arr = json_decode($response, true);


        $log_msg = "请求支付宝二维码,返回信息:{$response}";
        $return_arr = array(
            "msg" => 'fail',
            "status" => false,
        );
        if ($response_arr['alipay_trade_precreate_response']) {
            $data_str = json_encode($response_arr['alipay_trade_precreate_response']);

            if ($this->verify($data_str, $response_arr['sign'], '', 'RSA2')) {
                if ($response_arr['alipay_trade_precreate_response']['code'] == 10000) {
                    $return_arr['status'] = true;
                    $return_arr["msg"] = 'success';
                }
                $return_arr = array_merge($return_arr, $response_arr['alipay_trade_precreate_response']);

            } else {
                $log_msg .= ', 秘钥验证失败';
            }
        }

        //打印错误日志
        if (!$return_arr['status']) {
            if (Config::get("log_status")) {
                LogUtil::write($log_msg, LogUtil::ERR, LogUtil::FILE, "request_alipay_for_qrcode.log");
            }
        }
        return $return_arr;
    }


    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp
     * @return string
     */
    protected function buildRequestForm($para_temp)
    {

        $sHtml = "<form id='alipaysubmit' name='alipaysubmit' action='" . Config::get('alipay')['gatewayUrl'] . "?charset=UTF-8' method='POST'>";
        while (list ($key, $val) = each($para_temp)) {
            if (false === $this->checkEmpty($val)) {
                //$val = $this->characet($val, $this->postCharset);
                $val = str_replace("'", "&apos;", $val);
                //$val = str_replace("\"","&quot;",$val);
                $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $val . "'/>";
            }
        }

        //submit按钮控件请不要含有name属性
        $sHtml = $sHtml . "<input type='submit' value='ok' style='display:none;''></form>";

        $sHtml = $sHtml . "<script>document.forms['alipaysubmit'].submit();</script>";

        return $sHtml;
    }

    /**
     * 生成sign
     * @param $params
     * @return string
     */
    protected function getSignContent($params)
    {
        ksort($params);

        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    protected function sign($data, $signType = "RSA")
    {
        $priKey = Config::get('alipay')['merchant_private_key'];
        $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
            wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');

        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }


    //用公钥解密--验证签名是否正确
    protected function verify($data, $sign, $rsaPublicKeyFilePath = '', $signType = 'RSA')
    {
        $pubKey = Config::get('alipay')['alipay_public_key'];

        if ($pubKey) {
            $res = "-----BEGIN PUBLIC KEY-----\n" .
                wordwrap($pubKey, 64, "\n", true) .
                "\n-----END PUBLIC KEY-----";
        } else {
            //读取公钥文件
            $pubKey = file_get_contents($rsaPublicKeyFilePath);
            //转换为openssl格式密钥
            $res = openssl_get_publickey($pubKey);
        }

        ($res) or die('支付宝RSA公钥错误。请检查公钥文件格式是否正确');

        //调用openssl内置方法验签，返回bool值
        if ("RSA2" == $signType) {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res, OPENSSL_ALGO_SHA256);
        } else {
            $result = (bool)openssl_verify($data, base64_decode($sign), $res);
        }
        if ($this->checkEmpty($pubKey)) {
            //释放资源
            openssl_free_key($res);
        }
        return $result;
    }


    /**
     * 检测通知的sign是否和生成的sign相匹配
     * @param $params
     * @return bool
     */
    public function checkNoticeSign($params)
    {
        $sign = $params['sign'];
        $params['sign_type'] = null;
        $params['sign'] = null;
        return $this->verify($this->getSignContent($params), $sign,'','RSA2');
    }


    /**
     * 校验$value是否非空
     *  if not set ,return true;
     *    if is null , return true;
     **/
    protected function checkEmpty($value)
    {
        if (!isset($value))
            return true;
        if ($value === null)
            return true;
        if (trim($value) === "")
            return true;
        return false;
    }


    protected function generateSign($params, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $signType);
    }


}