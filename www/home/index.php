<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/27
 * Time: 15:17
 */

//项目根目录
define("WEB_PATH", __DIR__ . DIRECTORY_SEPARATOR);
define('ROOT_PATH', realpath(__DIR__ . '/../../') . "/");    // 根目录---用realpath(),这个方法很机智啊,这样可以直接看到绝对路径了,而且不会显示../../这种恶心的东西

//项目的目录
define('APP_PATH', ROOT_PATH . "app/");

//默认访问Home项目
//这个模块的名称,对应项目不同的文件夹
define('MODULE_NAME', "home");

define('DEBUG', true);//调试模式

define('MODULE_PATH', APP_PATH . MODULE_NAME . "/");//模块目录...也就是app模块


//这里指定加载配置文件---分为公共文件/和项目的文件,如果要分为线上和线下,,,公共配置和单独配置名称必须相同//默认为config

//define("LOAD_CONFIG", "online_config");//线上配置文件
//define("LOAD_CONFIG", "local_config");//本地配置文件
//载入composer
include ROOT_PATH . 'vendor/autoload.php';



