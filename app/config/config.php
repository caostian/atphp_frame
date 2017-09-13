<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 16:28
 */
//配置
return array(
    "web_name" => "test.caostian.com",

//    //路由方法
//    'route'=>[
//        'test'=>['index','test'],
//        'blog'=>['index','blog']
//    ],
//单数据库配置写法

    "mysql" => [
        //mycat 配置
        'db_name' => 'ATIAN',
        'db_host' => '192.168.80.128',
        'db_user' => 'root',
        'db_pwd' => '123456',
        'db_charset' => 'utf8',
        // 可选参数
        'db_port' => 8066,
        // 可选，定义表的前缀
        'db_table_prefix' => '',
    ],


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


    "socket" => [
        "user_auth" => [
            "host" => '192.168.66.160',
            "port" => "8400",
        ],
    ],


    //一维数据的配置方式
//        "oracle"=>[
//            'db_link' => '//192.168.66.160:1521/coobar',
//            'db_user' => 'game_user',
//            'db_pwd' => '123456',
//            'db_charset'=>'ZHS16GBK'
//        ],

    //二维数组的配置方式
    "oracle" => [
        //这个是读数据库 默认.
        "read" => [
            'db_link' => '//192.168.66.160:1521/coobar',
            'db_user' => 'game_user',
            'db_pwd' => '123456',
            'db_charset'=>'ZHS16GBK'
        ],
        //这个是写数据库--由于我操作写比较少
        "write" => [
            'db_link' => '//192.168.66.160:1521/coobar',
            'db_user' => 'game_user',
            'db_pwd' => '123456',
            'db_charset'=>'ZHS16GBK'
        ],
    ],

);