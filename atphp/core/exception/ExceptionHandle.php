<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/29
 * Time: 11:20
 * debug = false
 * 生产环境下,调试错误方法,如果报错,会自动记录到日志中
 *
 * debug = true ,直接用的是whoops 里面的界面,那个界面比较友好
 */

namespace atphp\exception;


use atphp\Request;
use atphp\util\DateUtil;
use atphp\util\HttpUtil;
use atphp\util\LogUtil;

class ExceptionHandle
{
    /**
     *
     * @param $errno
     * @param $errstr
     * @param $errfile
     * @param $errline
     * @throws ExceptionExt
     */
    public static function errorHandle($errno, $errstr, $errfile, $errline)
    {
        echo $errstr;
        throw new ExceptionExt($errstr, ExceptionExt::PHP_ERROR);
    }


    //捕获异常
    public static function exceptionHandle(ExceptionExt $e)
    {
        $error = $e->getError();


        switch ($error['code']) {
            case ExceptionExt::PHP_ERROR :
                //系统错误
                $path_name = "php_error.log";
                break;
            case  ExceptionExt::CODE_ERROR:
                //程序错误
                $path_name = "code_error.log";
                break;

            case  ExceptionExt::USER_ERROR:
                //用户错误
                $path_name = "user_error.log";
                break;

            case  ExceptionExt::DB_ERROR:
                //数据库错误
                $path_name = "db_error.log";
                break;

            default :
                //其他错误
                $path_name = "other_error.log";
        }
        $path_name = 'server/' . DateUtil::format(time(), "Ymd") . "/" . $path_name;

        LogUtil::write("程序错误信息是: " .stripslashes(var_export($error, true)), LogUtil::ERR, LogUtil::FILE, $path_name);

        if (substr($error['message'], 0, 1) == '@') {
            //如果第一个字符是@,敏感信息,不应该让用户看到
            $error['message'] = '程序出现异常';
        }

        if(Request::isAjax()){
            return array("status"=>false,"msg"=>$error["message"]);
        }else{
            //错误显示模板
            require_once CORE_PATH . "tpl/show_user_error.php";
        }


    }


}