<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/9
 * Time: 16:37
 */

namespace atphp;

/**
 * 获取请求数据类
 * Class Request
 * @package atphp
 */
class Request
{
    protected static $init;
    protected static $data;

    protected static function init(array $data = [])
    {
        self::$data = array_merge(self::get(), self::post(), $data);
        self::$init = true;
    }

    /**
     * 这里把参数转为整形,不区分post/get方式
     * @param $name
     * @return int|null
     */
    public static function getInteger($name)
    {
        !isset(self::$init) && self::init();
        if (isset(self::$data[$name])) {
            if (is_numeric(self::$data[$name])) {
                return self::$data[$name];
            }
        }
        return null;
    }

    /**
     * 这里把参数字符串转义
     * @param $name
     * @param string $func
     * @param bool $other_func
     * @return mixed
     */
    public static function getString($name, $func = "addslashes", $other_func = true)
    {
        !isset(self::$init) && self::init();
        self::$data[$name] = trim(self::$data[$name]);//减除空格
        //转实体
        if ($other_func) {
            self::$data[$name] = htmlentities(self::$data[$name]);
        }
        switch (strtolower($func)) {
            case "addslashes" :
                self::$data[$name] = addslashes(self::$data[$name]);
                break;
        }


        return self::$data[$name];
    }

    /**
     * 获取单个get 或者全部 get 数据
     * @param null $str
     * @return string
     */
    public static function get($str = null)
    {
        $return = $_GET;
        if (!is_null($str)) {
            $return = htmlspecialchars($return[$str]);
        }
        return $return;
    }

    /**
     * 获取单个get 或者全部 get 数据
     * @param null $str
     * @return string
     */
    public static function post($str = null)
    {
        $return = $_POST;
        if (!is_null($str)) {
            $return = htmlspecialchars($_REQUEST[$str]);
        }
        return $return;
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）,默认返回没有被代理的IP.
     * @return mixed
     */
    public static function getIP($type = 0, $adv = false)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if (null !== $ip) {
            return $ip[$type];
        }

        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }
                $ip = trim(current($arr));
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? [$ip, $long] : ['0.0.0.0', 0];
        return $ip[$type];
    }

    /**
     * 判断是否是https/http
     * @return bool
     */
    public static function isSsl()
    {
        $server = $_SERVER;
        if (isset($server['HTTPS']) && ('1' == $server['HTTPS'] || 'on' == strtolower($server['HTTPS']))) {
            return true;
        } elseif (isset($server['REQUEST_SCHEME']) && 'https' == $server['REQUEST_SCHEME']) {
            return true;
        } elseif (isset($server['SERVER_PORT']) && ('443' == $server['SERVER_PORT'])) {
            return true;
        } elseif (isset($server['HTTP_X_FORWARDED_PROTO']) && 'https' == $server['HTTP_X_FORWARDED_PROTO']) {
            return true;
        }
        return false;
    }

    /**
     * 当前URL地址中的scheme参数
     * @access public
     * @return string
     */
    public static function scheme()
    {
        return self::isSsl() ? 'https' : 'http';
    }


    protected static function http_method()
    {
        if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            return 'POST';
        } else {
            return 'GET';
        }
    }

    /**
     * 判断是否是post请求
     * @return bool
     */
    public static function isPost()
    {
        return self::http_method() == "POST";
    }

    /**
     * 判断是否是get请求
     * @return bool
     */
    public static function isGet()
    {
        return self::http_method() == "GET";
    }


    /**
     * 获取当前完整URL 包括QUERY_STRING
     * @return string
     *
     */
    public static function getAllUrl()
    {
        return $_SERVER['REQUEST_URI'];
    }


    /**
     * 获取当前包含协议的域名
     */
    public static function domain()
    {
        return self::scheme() . '://' . self::host();
    }

    /**
     * 当前域名,不包括协议
     * @return mixed
     */
    public static function host()
    {
        return $_SERVER["HTTP_HOST"];
    }
}