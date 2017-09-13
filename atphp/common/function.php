<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 15:42
 */

/* ========================================================================
 * 全局函数
 * 更漂亮的数组或变量的展现方式
 */
//function p($var)
//{
//    if (is_cli()) {
//        if (is_array($var) || is_object($var)) {
//            dump($var);
//        } else {
//            echo PHP_EOL;
//            echo "\e[31m" . $var . "\e[37m" . PHP_EOL;
//            echo PHP_EOL;
//        }
//    } else {
//        if (is_bool($var)) {
//            var_dump($var);
//        } else if (is_null($var)) {
//            var_dump(NULL);
//        } else {
//            echo "<pre style='position:relative;z-index:1000;padding:10px;border-radius:5px;background:#F5F5F5;border:1px solid #aaa;font-size:14px;line-height:18px;opacity:0.9;'>" . print_r($var, true) . "</pre>";
//        }
//    }
//}

function debug()
{
    $var = func_get_args();
    if (function_exists('dump')) {
        array_walk($var, function ($v) {
            dump($v);
        });
    } else {
        array_walk($var, function ($v) {
            print_r($v);
        });
    }
    exit();
}

function is_cli()
{
    return PHP_SAPI == 'cli';
}


function redirect($str)
{
    header('Location:' . $str);
}

function json(Array $array)
{
    header('Content-Type:application/json; charset=utf-8');
    echo json_encode($array);
    exit;
}

function show404()
{
    header('HTTP/1.1 404 Not Found');
    header("status: 404 Not Found");

    echo "<h1 style='text-align: center;margin-top: 15%;'>404错误--没有找到该页面</h1>";
    exit();
}


/**
 * 自动加载类
 * @param string $class 需要加载的类,需要带上命名空间
 */
function auto_load($class)
{
    $class = str_replace(['\\', 'atphp'], ['/', ''], trim($class, '\\')) . ".php";

    if (is_file(CORE_PATH . $class)) {
        include_once CORE_PATH . $class;
    } elseif (is_file(APP_PATH . $class)) {
        include_once APP_PATH . $class;
    } else if (is_file(ROOT_PATH . $class)) {
        include_once ROOT_PATH . $class;
    } else {
        include_once $class;
    }

}

/**
 * 获取毫秒级别的时间戳
 */
function getMillisecond()
{
    //获取毫秒的时间戳
    $time = explode(" ", microtime());
    $time = $time[1] . ($time[0] * 1000);
    $time2 = explode(".", $time);
    $time = $time2[0];
    return $time;
}



