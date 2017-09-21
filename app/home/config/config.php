<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/3
 * Time: 11:54
 */
//这里面的配置优先,公共配置如果相同会覆盖掉

return array(
    //支付宝配置信息
    'alipay' => [
        //应用ID,您的APPID。
        'app_id' => "",
        //商户私钥，您的原始格式RSA私钥
        'merchant_private_key' => "",
        //支付宝公钥
        'alipay_public_key' => "",

        //异步通知地址
        'notify_url' => "http://www.test.com/AlipayNotice/notify_url",
        //同步跳转
        'return_url' => "http://www.test.com/AlipayNotice/return_url",
        //支付宝网关
        'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
    ],
    //微信支付配置
    'weixin' => [
        //异步通知地址
        'notify_url' => "http://www.test.com/WeixinNotice/notify_url",

        'appid' => '',//绑定支付的APPID
        'mchid' => '',//商户号
        'key' => '',//商户支付密钥
        'appsecret' => '',
        //证书路径
        'sslcert_path' => APP_PATH . 'lib/WeiXinCert/apiclient_cert.pem',
        'sslkey_path' => APP_PATH . 'lib/WeiXinCert/apiclient_key.pem',
    ],


);