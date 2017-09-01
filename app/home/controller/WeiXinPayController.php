<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/28
 * Time: 15:10
 */

namespace home\controller;


use atphp\Config;
use service\WeiXinPayService;


class WeiXinPayController
{
    //微信二维码支付
    public function wx_qrcode()
    {
        $data = [
            'body' => 'test',
            'detail' => 'test1111',
            'attach' => 'ha',
            'out_trade_no' => time(),//订单号
            'total_fee' => 1,//分
            'time_start' => date("YmdHis", time()),//开始时间
            'time_expire' => date("YmdHis", time() + 1800),//过期时间
            'trade_type' => 'NATIVE',
            'openid' => '',
            'notify_url'=>Config::get('weixin')["notify_url"],
            'product_id'=>'123',//trade_type=NATIVE时（即扫码支付），此参数必传。此参数为二维码中包含的商品ID，商户自行定义。
        ];

        $wx_service = new WeiXinPayService();
        $result = $wx_service->qrcodePay($data);

        if ($result['status']) {
            require_once APP_PATH . "lib/QRcode/phpqrcode.php";
            \QRcode::png($result['code_url']);
        } else {
            var_export($result);
        }
    }
}