<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/2
 * Time: 15:55
 * 文件操作
 */
namespace atphp\util;

class FileUtil
{
    /**
     * 读取文件
     *
     * @param string $file 文件名
     * @return string 文件内容
     */
    public static function read($file)
    {
        $data = @file_get_contents($file);
        return $data;
    }

    /**
     * 写入文件
     *
     * @param string $fileName 文件名
     * @param string $data 文件内容
     * @param integer $flags 写入类型  FILE_APPEND: 附加, LOCK_EX: 独占
     * @return boolean
     */
    public static function write($fileName, $data, $flags = 0)
    {
        $dirName = dirname($fileName);
        if (!is_dir($dirName)) {
            Ext_Dir::mkDirs($dirName);
        }
        $rs = file_put_contents($fileName, $data, $flags);
        return $rs;
    }

    public static function writeArray($file, $array)
    {
        $content = "<?php\nif (!defined('APP_PATH')) die('error');\nreturn "
            . var_export($array, true) . ";";
        $rs = self::write($file, $content);
        return $rs;
    }

    /**
     * 格式化字节大小
     *
     * @param integer $sizeInput 字节数
     * @return string 格式化后的信息
     */
    public static function formatSize($sizeInput)
    {
        $sizeInput = doubleval($sizeInput);
        if ($sizeInput >= 1024 * 1024 * 1024) {
            $sizeOutput = sprintf("%01.2f", $sizeInput / (1024 * 1024 * 1024)) . " GB";
        } elseif ($sizeInput >= 1024 * 1024) {
            $sizeOutput = sprintf("%01.2f", $sizeInput / (1024 * 1024)) . " MB";
        } elseif ($sizeInput >= 1024) {
            $sizeOutput = sprintf("%01.2f", $sizeInput / 1024) . " KB";
        } else {
            $sizeOutput = $sizeInput . " Bytes";
        }
        return ($sizeOutput);
    }

    /**
     * 获取一个文件所在的路径
     *
     * @param string $file 文件名
     * @return string 所在的路径
     */
    public static function getDir($file)
    {
        return pathinfo($file, PATHINFO_DIRNAME);
    }

    /**
     * 获取一个文件的基础文件名
     *
     * @param string $file 文件名
     * @return string 基础文件名
     */
    public static function getName($file)
    {
        return pathinfo($file, PATHINFO_BASENAME);
    }


    //创建单个目录
    public static function  mkdir($dir, $mode = 0777)
    {
        if (!is_dir($dir)) {
            return mkdir($dir, $mode,true);
        }else{
            return true;
        }
    }

    // 批量创建目录
    public static function mkdirs($dirs, $mode = 0777)
    {
        foreach ($dirs as $dir) {
            self::mkdir($dir, $mode);
        }
    }
}