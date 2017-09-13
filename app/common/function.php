<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 12:27
 * 普通函数
 */

//转换编码函数--这个比较方便而已
function icovGbkToUtf8($msg)
{
    return $msg ? iconv('GBK', 'UTF-8', $msg) : '';
}

function icovUtf8ToGbk($msg)
{
    return $msg ? iconv('UTF-8', 'GBK', $msg) : '';
}