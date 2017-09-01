<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/27
 * Time: 15:17
 * 这是一个支付的单独项目,里面封装了一写支付/微信相关的支付,因为这个实在用的太多了,又不想用微信/支付宝的sdk,太难以维护了
 */

//项目根目录
define('ROOT_PATH', realpath(__DIR__ . '/../../') . "/");    // 根目录---用realpath(),这个方法很机智啊,这样可以直接看到绝对路径了,而且不会显示../../这种恶心的东西

//项目的目录
define('APP_PATH', ROOT_PATH . "app/");

//默认访问Home项目
//这个模块的名称,对应项目不同的文件夹
define('MODULE_NAME', "home");

define('DEBUG', true);//调试模式

define('MODULE_PATH', APP_PATH . MODULE_NAME . "/");//模块目录...也就是app模块

//这里指定加载配置文件---分为公共文件/和项目的文件,如果要分为线上和线下,,,公共配置和单独配置名称必须相同//默认为config
define("LOAD_CONFIG", "config");

//载入composer
include ROOT_PATH . 'vendor/autoload.php';