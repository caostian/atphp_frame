<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25
 * Time: 17:31
 * 微信支付通知
 */

namespace home\controller;


use atphp\Controller;
use atphp\util\LogUtil;
use atphp\util\XmlUtil;
use service\WeiXinPayService;


class WeixinNoticeController extends Controller
{

    /****
     * 回调信息
     * array (
     * 'appid' => '',
     * 'attach' => 'ha',
     * 'bank_type' => 'CFT',
     * 'cash_fee' => '1',
     * 'fee_type' => 'CNY',
     * 'is_subscribe' => 'N',
     * 'mch_id' => '',
     * 'nonce_str' => 'dnlrad1ej62fpcb1wimklahkvg2yonnj',
     * 'openid' => '',
     * 'out_trade_no' => '1503904638',
     * 'result_code' => 'SUCCESS',
     * 'return_code' => 'SUCCESS',
     * 'sign' => '',
     * 'time_end' => '20170828151745',
     * 'total_fee' => '1',
     * 'trade_type' => 'NATIVE',
     * 'transaction_id' => '',
     * )
     */
    public function notify_url()
    {
        $xml = file_get_contents("php://input");


        $result = XmlUtil::decode($xml);
        $wx_service = new WeiXinPayService();
        //验证秘钥
        if ($wx_service->checkNoticeSign($result)) {
            if (isset($result['result_code']) && isset($result['return_code']) && $result['result_code'] == 'SUCCESS' && $result['return_code'] == 'SUCCESS') {
                //这里支付成功
                $result_msg = '支付成功';
            } else {
                //支付失败
                $result_msg = '支付失败';
            }
        } else {
            //秘钥验证失败
            $result_msg = '秘钥验证失败';
        }

        LogUtil::write($result_msg . '-->' . var_export($result, true), LogUtil::NOTICE, LogUtil::FILE, "request_wxpay_for_notify.LogUtil");
    }
}