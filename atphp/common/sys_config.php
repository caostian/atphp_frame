<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/4
 * Time: 15:26
 * 系统默认配置文件,,注意,所有的配置文件都是小写
 */

return array(
//    "web_name"=>"www.caositan.com",
    'open_restful' => false,
    'timezone' => 'Asia/Shanghai',
    //默认控制器和方法
    'default_controller' => 'Index',
    'default_action' => 'index',
    //模板后缀
    "template_suffix" => "html",

    //日志配置
    "log_file_size" => 20971520,    // 日志文件大小限制
    'log_status' => true,   // 是否记录日志
    'log_record_level' => array('EMERG', 'ALERT', 'CRIT', 'ERR'),// 允许记录的日志级别
    'log_path' => RUNTIME_PATH . "system_log/",//默认日志位置

    //一次用户请求保留的sql 查询语句
    '_db_sql_query' => [], //系统保留变量,不需要去动它
    'db_sql_debug_model' => false,
    'db_slow_sql_log' => true,//开启慢查询日志
    'db_slow_sql_time' => 2000,//慢查询2秒钟
    'db_charset' => 'utf-8',


    //路由方法
//    'route'=>[
//        'test'=>['index','test'],
//        'blog'=>['index','blog']
//    ],


//单数据库配置写法

//"mysql" =>[
//    //mysql示例配置
//    'db_name' => 'test',
//    'db_host' => '127.0.0.1',
//    'db_user' => 'root',
//    'db_pwd' => '123456',
//    'db_charset' => 'utf8',
//    // 可选参数
//    'db_port' => 3306,
//    // 可选，定义表的前缀
//    'db_table_prefix' => '',
//],


    //多数据库配置方法

//    "mysql" => [
//        "config_1" => [
//            //mysql示例配置
//            'db_name' => 'test',
//            'db_host' => '127.0.0.1',
//            'db_user' => 'root',
//            'db_pwd' => '123456',
//            'db_charset' => 'utf8',
//            // 可选参数
//            'db_port' => 3306,
//            // 可选，定义表的前缀
//            'db_table_prefix' => '',
//        ],
//        "config_2" => [
//            //mysql示例配置
//            'db_name' => 'test',
//            'db_host' => '192.168.66.160',
//            'db_user' => 'root',
//            'db_pwd' => '123123',
//            'db_charset' => 'utf8',
//            // 可选参数
//            'db_port' => 3306,
//            // 可选，定义表的前缀
//            'db_table_prefix' => '',
//        ],
//    ],


    //这里是cookie配置
    'cookie' => [
        // cookie 名称前缀
        'prefix' => 'atphp_',
        // cookie 保存时间
        'expire' => 0,
        // cookie 保存路径
        'path' => '/',
        // cookie 有效域名
        'domain' => '',
        //  cookie 启用安全传输
        'secure' => false,
        // httponly设置
        'httponly' => true, //默认不会被javascript获取到,如果需要用js获取的话,注意,一定要把这个关掉,或者动态的设置配置
        // 是否使用 setcookie
        'setcookie' => true,
    ],


    //sessoin 配置
    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'think',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
        'httponly' => 'true',//不允许javascript获取到对象
    ],

    //缓存配置
    'cache' => [
        'type' => 'File',
        'path' => RUNTIME_PATH . "cache/",
        'prefix' => '',
        'expire' => 0,
    ],

);