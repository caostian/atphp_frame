<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 11:56
 */

namespace oracleModel;


use atphp\exception\ExceptionExt;
use atphp\util\DateUtil;
use atphp\util\LogUtil;

class BaseOracleModel
{
    protected $hander = null;

    public function __construct($oracleConfigKey = null)
    {
        $this->hander = BaseOracle::connect($oracleConfigKey);
    }

    public function checkError($stat, $cur = null)
    {
        $path_name = 'server/' . DateUtil::format(time(), "Ymd") . "/db_error.log";
        if ($stat && $e = oci_error($stat)) {
            $e["message"] = icovGbkToUtf8($e['message']);

            //一遍数据库都要try_cache,所以我直接来记录下日志,不然捕获了,我就没法获得报错信息了
            LogUtil::write("数据库错误信息: " .stripslashes(var_export($e, true)), LogUtil::ERR, LogUtil::FILE, $path_name);

            throw  new ExceptionExt("@" . var_export($e, true));
        }
        if ($cur && $e = oci_error($cur)) {
            $e["message"] = icovGbkToUtf8($e['message']);
            LogUtil::write("数据库错误信息: " .stripslashes(var_export($e, true)), LogUtil::ERR, LogUtil::FILE, $path_name);
            throw  new ExceptionExt("@" . var_export($e, true));
        }
    }
}