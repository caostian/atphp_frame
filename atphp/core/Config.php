<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 16:24
 * 这里是加载配置文件的方法
 */

namespace atphp;

use atphp\util\ArrayUtil;

class Config
{
    /**
     * 用来存储已经加载的配置
     *
     * @var array
     */
    static private $conf = array();

    /**
     * 加载系统配置,如果之前已经加载过,那么就直接返回
     * @param $name
     * @return bool|mixed
     */
    static public function get($name='')
    {
        //判断配置存不存在
        if(!$config_file  = LOAD_CONFIG){
            $config_file = "config";
        }
        if(empty(self::$conf)){
            //这里又多种情况
            //1.判断当前是哪个模块,模块里面有没有配置文件
            //2.公共配置有没有文件,
            //3.这两种文件数组合并

            $public_config_file = APP_PATH."config/".$config_file.".php";
            $module_config_file  = MODULE_PATH."config/".$config_file.".php";
            $module_config = [];
            $public_config = [];

            if(is_file($public_config_file)){
                $public_config =  include $public_config_file?:[];
            }

            if(is_file($module_config_file)){
                $module_config = include $module_config_file?:[];
            }

            //不管大小写,都转化为大写
            //系统配置
            $system_config = include COMMON_PATH.'sys_config.php';

            //把键名转大写
            self::$conf  = array_merge($system_config,$public_config,$module_config);
            if($name==''){
                //如果等于空的话,那么获取全部配置信息
                return self::$conf;
            }
        }

        //获取二维数组里面的值
        if(strpos($name, '.')!==false){
            $name = explode(".",$name);
            return isset(self::$conf[$name[0]][$name[1]]) ? self::$conf[$name[0]][$name[1]]  : false;
        }

        return isset(self::$conf[$name])?self::$conf[$name]:false;
    }

    //设置配置
    static public function set($name,$value){
        self::$conf[$name] = $value;
    }
}