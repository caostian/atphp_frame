<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 17:27
 * 支付控制器---自给封装的一些支付方法,包括,支付宝web支付,支付宝扫码支付,微信扫码支付
 */

namespace home\controller;

use \atphp\Controller;


class PayController extends Controller
{
    /***
     * 注意事项:
     * 1.配置文件,里面主要参数需要配置
     * 2.微信支付需要导入证书,app/lib/WeiXinCert.这个文件夹导入微信证书,不然就会出问题
     *
     */
    public function index()
    {
        echo '<a href="/Alipay/alipay_web">支付宝网页支付</a>';
        echo '<br/>';
        echo '<a href="/Alipay/alipay_qrcode">支付宝二维码支付</a>';
        echo '<br/>';
        echo '<a href="/WeiXinPay/wx_qrcode">微信二维码支付</a>';
    }


}