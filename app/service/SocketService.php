<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/12
 * Time: 17:44
 * socket连接基础方法 ---就一个单例
 */

namespace service;


use atphp\exception\ExceptionExt;

class SocketService
{
    protected $timeout;
    public static $hander = null;

    private function __construct()
    {
        //我就不写
    }

    public static function connect($host, $port, $timeout = 5)
    {
        if (!$host || !$port) {
            throw new ExceptionExt("socket host/port 不能为空");
        }
        //如果不存在,就实例化这个单例
        if (!self::$hander) {
            self::$hander = fsockopen($host, $port, $errno, $errstr, $timeout);
            if (!self::$hander) {
                throw  new ExceptionExt("@socket 连接失败,错误信息是:" . $errstr);
            }
            stream_set_blocking(self::$hander, TRUE);
            stream_set_timeout(self::$hander, 5);
        }
        return self::$hander;
    }

    //关闭连接
    public static function close()
    {
        if (self::$hander) {
            fclose(self::$hander);
            self::$hander = null;
        }
    }

    //私有克隆函数，防止外办克隆对象
    private function __clone()
    {
    }


}