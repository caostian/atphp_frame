<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29
 * Time: 15:19
 * 定义调试信息
 */

//打开PHP的错误显示
ini_set('display_errors',true);

//这里开启错误提示
if(DEBUG && PHP_SAPI != 'cli') {
    //载入友好的错误显示类
    $whoops = new \Whoops\Run;
    $errorPage = new \Whoops\Handler\PrettyPageHandler;
    $errorPage->setPageTitle("PHP报错了,要注意了哇!");
    $whoops->pushHandler($errorPage);
    $whoops->register();
} else {
     set_error_handler(array('\atphp\exception\ExceptionHandle','errorHandle'));
     set_exception_handler(array('\atphp\exception\ExceptionHandle','exceptionHandle'));
}
