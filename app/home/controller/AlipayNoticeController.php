<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/25
 * Time: 10:07
 * 支付宝通知
 */

namespace home\controller;


use atphp\Controller;
use atphp\util\LogUtil;
use service\AlipayService;


class AlipayNoticeController extends Controller
{

    /*****
     * 返回信息
     * array (
     * 'gmt_create' => '2017-08-25 10:52:20',
     * 'charset' => 'utf-8',
     * 'seller_email' => '',
     * 'open_id' => '',
     * 'subject' => 'woshishui',
     * 'sign' => '',
     * 'body' => 'test_cst',
     * 'buyer_id' => '',
     * 'invoice_amount' => '0.01',
     * 'notify_id' => '',
     * 'fund_bill_list' => '[{"amount":"0.01","fundChannel":"PCREDIT"}]',
     * 'notify_type' => 'trade_status_sync',
     * 'trade_status' => 'TRADE_SUCCESS',
     * 'receipt_amount' => '0.01',
     * 'app_id' => '',
     * 'buyer_pay_amount' => '0.01',
     * 'sign_type' => 'RSA2',
     * 'seller_id' => '',
     * 'gmt_payment' => '2017-08-25 10:52:23',
     * 'notify_time' => '2017-08-25 10:52:24',
     * 'version' => '1.0',
     * 'out_trade_no' => '1503629531',
     * 'total_amount' => '0.01',
     * 'trade_no' => '2017082521001004590206630559',
     * 'auth_app_id' => '',
     * 'buyer_LogUtilon_id' => '',
     * 'point_amount' => '0.00',
     * )
     */
    //支付宝通知
    public function notify_url()
    {
        $request_data = $_POST;

        $alipay_service = new AlipayService();
        $result = $alipay_service->checkNoticeSign($request_data);

        if ($result) {
            if ($request_data['trade_status'] == 'WAIT_BUYER_PAY') {
                //这里等待付款,就不执行东西了,直接返回
                return;
            }
            if ($request_data['trade_status'] == 'TRADE_SUCCESS') {
                //这里处理支付成功逻辑
                echo 'success';
                return;
            } else if ($request_data['trade_status'] == 'TRADE_FINISHED') {
                //这里不需要处理,已经处理过了,直接返回success就可以了
                echo 'success';
                return;
            } else {
                $result_msg = '状态不是成功状态:' . $request_data['trade_status'];
            }
        } else {
            //秘钥验证失败
            $result_msg = '秘钥验证失败';
        }
        LogUtil::write($result_msg . '-->' . var_export($request_data, true), LogUtil::NOTICE, LogUtil::FILE, "request_alipay_for_notify.LogUtil");
        //剩余的全是失败
        echo "fail";

    }

    /***
     * 返回信息
     * array (
     * 's' => 'AlipayNotice/return_url',
     * 'total_amount' => '0.01',
     * 'timestamp' => '2017-08-25 14:54:30',
     * 'sign' => '',
     * 'trade_no' => '2017082521001004590207128641',
     * 'sign_type' => 'RSA2',
     * 'auth_app_id' => '',
     * 'charset' => 'UTF-8',
     * 'seller_id' => '2088221931936835',
     * 'method' => 'alipay.trade.wap.pay.return',
     * 'app_id' => '',
     * 'out_trade_no' => '1503644047',
     * 'version' => '1.0',
     * )
     */

    //支付宝同步跳转通知
    public function return_url()
    {
        $request_data = $_GET;
        //不去掉这个,验证不成功
        $request_data["s"] = null;

        $alipay_service = new AlipayService();
        $result = $alipay_service->checkNoticeSign($request_data);

        if ($result) {
            if ($request_data['trade_status'] == 'TRADE_SUCCESS') {
                echo 'success';
                return;
            } else if ($request_data['trade_status'] == 'TRADE_FINISHED') {
                //这里不需要处理,已经处理过了,直接返回success就可以了
                echo 'success';
                return;
            } else {
                $result_msg = '状态不是成功状态:' . $request_data['trade_status'];
            }
        } else {
            //秘钥验证失败
            $result_msg = '秘钥验证失败';
        }
        LogUtil::write($result_msg . '-->' . var_export($request_data, true), LogUtil::NOTICE, LogUtil::FILE, "request_alipay_for_return.LogUtil");
        //剩余的全是失败
        echo "fail";

    }
}