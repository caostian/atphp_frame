<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/9
 * Time: 16:07
 * 定义各种变量
 */
define('ATPHP_VERSION','1.0.0');//当前框架的版本

define('REQUEST_TIME', $_SERVER['REQUEST_TIME']);//请求的时间
//框架的路径
define("FRAME_PATH",ROOT_PATH.'atphp/');
//项目的核心目录
define("CORE_PATH",FRAME_PATH."core/");
//普通文件的一些文件
define("COMMON_PATH",FRAME_PATH."common/");

define("RUNTIME_PATH",APP_PATH."runtime/");

define("CACHE_PATH",RUNTIME_PATH."cache/");