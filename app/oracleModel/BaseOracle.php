<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 10:49
 * 一个oracle 连接的基本类,我就封装一个连接,其他的就不封装了
 */
namespace oracleModel;

use atphp\Config;
use atphp\exception\ExceptionExt;

class BaseOracle
{
    private static $header = [];


    private function __construct()
    {

    }

    public static function connect($oracleConfigKey = null)
    {

        if ($oracleConfigKey) {
            //有用key来加载配置
            $config = Config::get("oracle")[$oracleConfigKey];
            if (!$config) {
                throw new ExceptionExt("oracle数据库配置没有定义");
            }
        } else {
            $config = Config::get("oracle");
            //没有用key来加载配置,那么默认就选第一个配置好了
            if (count($config) != count($config, true)) {
                //二维数组
                $oracleConfigKey = array_keys($config)[0]; //获取键名
                $config = array_values($config)[0];//如果是二维数组,默认取第一个

            } else {
                $oracleConfigKey = 'default'; //如果没有就设置默认键名
            }
        }


        if (!isset(self::$header[$oracleConfigKey]) || !self::$header[$oracleConfigKey]) {
            $db_charset = isset($config['db_charset'])?$config['db_charset']:'ZHS16GBK';
            $hander = @oci_connect($config['db_user'], $config['db_pwd'], $config['db_link'], $db_charset);
            if (!$hander) {
                throw new ExceptionExt("连接oracle数据库失败");
            }

            self::$header[$oracleConfigKey] = $hander;
        }

        return self::$header[$oracleConfigKey];
    }

    //私有克隆函数，防止外部克隆对象
    private function __clone()
    {
    }

}