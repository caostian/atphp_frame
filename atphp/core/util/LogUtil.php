<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/4
 * Time: 15:08
 *
 */
namespace atphp\util;
use atphp\Config;

class LogUtil
{
    const EMERG   = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT   = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT    = 'CRIT';   // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR     = 'ERR';    // 一般错误: 一般性错误
    const WARN    = 'WARN';   // 警告性错误: 需要发出警告的错误
    const NOTICE  = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO    = 'INFO';   // 信息: 程序输出信息
    const DEBUG   = 'DEBUG';  // 调试: 调试信息
    const SQL     = 'SQL';    // SQL：SQL语句 注意只在调试模式开启时有效

    const SYSTEM  = 0;
    const MAIL    = 1;
    const TCP     = 2;
    const FILE    = 3;

    static $log   = array();

    static function record($message,$level=self::ERR,$record=false) {
        if($record || in_array($level,Config::get("log_record_level"))) {
            $now = date("Y-m-d H:i:s");
            self::$log[] = "[".$now."] {$level}: {$message}\r\n";
        }
    }

    static function save($type=self::FILE,$destination='',$extra='')
    {
        if(empty($destination)){
            $destination = date('Ymd').".log";
        }
        $log_path = Config::get("log_path");
        if (!is_dir($log_path)) FileUtil::mkdir($log_path);

        $destination = $log_path.$destination;

        if(self::FILE == $type) {
            if(is_file($destination) && floor(Config::get("log_file_size")) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.basename($destination).".".time());
        }
        error_log(implode("",self::$log),$type,$destination,$extra);
        self::$log = array();
    }

    static function write($message,$level=self::ERR,$type=self::FILE,$destination='',$extra='')
    {
        $now = date("Y-m-d H:i:s");

        if(empty($destination)){
            $destination = date('Ymd').".log";
        }
        $log_path = Config::get("log_path");



        $destination = $log_path.$destination;



        if (!is_dir(dirname($destination))) FileUtil::mkdir(dirname($destination));

        if(self::FILE == $type) {
            if(is_file($destination) && floor(Config::get("log_file_size")) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.basename($destination).".".time());
        }
        error_log("[".$now."] {$level}: {$message}\r\n\r\n\r\n",$type,$destination,$extra);
    }
}