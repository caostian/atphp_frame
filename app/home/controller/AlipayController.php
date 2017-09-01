<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/25
 * Time: 17:36
 * 调用支付接口
 */
namespace home\controller;

use atphp\Controller;
use service\AlipayService;


class  AlipayController extends Controller
{
    //支付宝网页支付
    public function alipay_web()
    {
        //支付宝支付请求
        $request = [
            'productCode' => 'QUICK_WAP_WAY',
            'body' => 'test_cst',
            'subject' => "nihaoma",
            'out_trade_no' => time(),
            'timeout_express' => '30m',
            'total_amount' => 0.01,
            'passback_params' => '',//公共回传参数
        ];

        $alipayService = new AlipayService();
        $alipayService->webPay($request);
    }

    //支付宝二维码支付
    public function alipay_qrcode()
    {
        $request = [
            'body' => 'test_cst',
            'subject' => "woshishui",
            'out_trade_no' => time(),
            'timeout_express' => '30m',
            'total_amount' => 0.01,
            'passback_params' => '',//公共回传参数
        ];

        $alipayService = new AlipayService();
        $data = $alipayService->qrcodePay($request);

        if ($data['status']) {
            require_once APP_PATH . "lib/QRcode/phpqrcode.php";
            \QRcode::png($data['qr_code']);
        } else {
            var_export($data);
        }

    }

}