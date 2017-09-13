<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 15:36
 */

//设置默认市区
header("Content-type: text/html; charset=utf-8");
//加载定义文件
require_once __DIR__ . "/common/define.php";
//加载类库
include COMMON_PATH . 'function.php';

include APP_PATH."common/function.php";

//注册自动加载
spl_autoload_register('auto_load');
//系统调试功能
require_once __DIR__ . "/common/debug.php";


date_default_timezone_set(\atphp\Config::get('timezone'));


//自动建立运行时目录
if (!is_dir(APP_PATH . "runtime")) \atphp\util\FileUtil::mkdir(APP_PATH . "runtime");


if (PHP_SAPI == 'cli') {
    //   \atphp\CLI_ATPHP::run();
} else {

    //开始跑框架
    \atphp\ATPHP::run();

}

