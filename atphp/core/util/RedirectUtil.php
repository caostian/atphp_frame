<?php
namespace atphp\util;

use atphp\Request;

class RedirectUtil
{
    /**
     * 重定向传值（通过Session）
     * @access protected
     * @param string|array $name 变量名或者数组
     * @param mixed $value 值
     */
    public static function with($name, $value = null)
    {
        if (is_array($name)) {
            foreach ($name as $key => $val) {
                Session::flash($key, $val);
            }
        } else {
            Session::flash($name, $value);
        }
    }


    /**
     * 跳转的地址
     * @param string $url URL地址
     * @param array $param 参数
     */
    public static function goUrl($url = '', $param = [])
    {
        if (strpos($url, "http") === false) {
            $url = Request::domain() . "/" . trim($url, "/");
        }

        if ($param) {
            $url .= http_build_query($param);
        }

        if ($url) {
            header("location:" . $url);
        }
        exit;
    }

}
