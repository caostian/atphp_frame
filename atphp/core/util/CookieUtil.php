<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2017 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

namespace atphp\util;

use atphp\Config;

class CookieUtil
{
    protected static $config = [
        // cookie 名称前缀
        'prefix'    => '',
        // cookie 保存时间
        'expire'    => 0,
        // cookie 保存路径
        'path'      => '/',
        // cookie 有效域名
        'domain'    => '',
        //  cookie 启用安全传输
        'secure'    => false,
        // httponly设置
        'httponly'  => '', //这个属性规定，不许通过客户端脚本访问cookie。使用HTTP-only Cookie后，Web 站点就能排除cookie中的敏感信息被发送给黑客的计算机或者使用脚本的Web站点的可能性
        // 是否使用 setcookie
        'setcookie' => true,
    ];

    protected static $init;

    /**
     * Cookie初始化
     * @param array $config
     * @return void
     */
    public static function init(array $config = [])
    {
        if (empty($config)) {
            $config = Config::get('cookie');
        }
        self::$config = array_merge(self::$config, array_change_key_case($config));
        if (!empty(self::$config['httponly'])) {
            ini_set('session.cookie_httponly', 1);
        }
        self::$init = true; //说明这个已经初始化了
    }

    /**
     * 设置或者获取cookie作用域（前缀）
     * @param string $prefix
     * @return string|void
     */
    public static function prefix($prefix = '')
    {
        if (empty($prefix)) {
            return self::$config['prefix'];
        }
        self::$config['prefix'] = $prefix;
    }

    /**
     * Cookie 设置、获取、删除
     *
     * @param string $name  cookie名称
     * @param mixed  $value cookie值
     * @param mixed  $option 可选参数 可能会是 null|integer|string  如果是string expire=100&perfix=atphp,这样也可以解析成一个数组
     *
     * @return mixed
     * @internal param mixed $options cookie参数
     */
    public static function set($name, $value = '', $option = null)
    {
        !isset(self::$init) && self::init();
        // 参数设置(会覆盖黙认设置)
        if (!is_null($option)) {
            if (is_numeric($option)) {
                $option = ['expire' => $option];
            } elseif (is_string($option)) {
                parse_str($option, $option); //查询的字符串解析到变量中
            }
            $config = array_merge(self::$config, array_change_key_case($option));
        } else {
            $config = self::$config;
        }
        $name = $config['prefix'] . $name;
        // 设置cookie
        if (is_array($value)) {
            array_walk_recursive($value, 'self::jsonFormatProtect', 'encode'); //遍历每个数组元素,把元素都encode一下
            $value = 'atphp:' . json_encode($value);
        }
        $expire = !empty($config['expire']) ? $_SERVER['REQUEST_TIME'] + intval($config['expire']) : 0; //如果不设置过期时间,那么关闭浏览器就失效了
        if ($config['setcookie']) {
            setcookie($name, $value, $expire, $config['path'], $config['domain'], $config['secure'], $config['httponly']);
        }
        $_COOKIE[$name] = $value;
    }

    /**
     * 永久保存Cookie数据
     * @param string $name  cookie名称
     * @param mixed  $value cookie值
     * @param mixed  $option 可选参数 可能会是 null|integer|string
     * @return void
     */
    public static function forever($name, $value = '', $option = null)
    {
        if (is_null($option) || is_numeric($option)) {
            $option = [];
        }
        $option['expire'] = 315360000; //保存10年
        self::set($name, $value, $option);
    }

    /**
     * 判断Cookie数据 判断cookie 是否存在
     * @param string        $name cookie名称
     * @param string|null   $prefix cookie前缀
     * @return bool
     */
    public static function has($name, $prefix = null)
    {
        !isset(self::$init) && self::init();
        $prefix = !is_null($prefix) ? $prefix : self::$config['prefix'];
        $name   = $prefix . $name;
        return isset($_COOKIE[$name]);
    }

    /**
     * Cookie获取
     * @param string        $name cookie名称
     * @param string|null   $prefix cookie前缀
     * @return mixed
     */
    public static function get($name = '', $prefix = null)
    {
        !isset(self::$init) && self::init();
        $prefix = !is_null($prefix) ? $prefix : self::$config['prefix'];
        $key    = $prefix . $name;

        if ('' == $name) {
            // 获取全部
            if ($prefix) {
                $value = [];
                foreach ($_COOKIE as $k => $val) {
                    if (0 === strpos($k, $prefix)) {
                        $value[$k] = $val;
                    }
                }
            } else {
                $value = $_COOKIE;
            }
        } elseif (isset($_COOKIE[$key])) {
            $value = $_COOKIE[$key]; //这是直接获取key ,

            //如果存在数组的标识前缀 atphp: ,那么就是数组
            if (0 === strpos($value, 'atphp:')) {
                $value = substr($value, 6);
                $value = json_decode($value, true);
                array_walk_recursive($value, 'self::jsonFormatProtect', 'decode'); //把每个数组
            }
        } else {
            $value = null;
        }
        return $value;
    }

    /**
     * Cookie删除
     * @param string        $name cookie名称
     * @param string|null   $prefix cookie前缀
     * @return mixed
     */
    public static function delete($name, $prefix = null)
    {
        !isset(self::$init) && self::init();
        $config = self::$config;
        $prefix = !is_null($prefix) ? $prefix : $config['prefix'];
        $name   = $prefix . $name;
        if ($config['setcookie']) {
            setcookie($name, '', $_SERVER['REQUEST_TIME'] - 3600, $config['path'], $config['domain'], $config['secure'], $config['httponly']);
        }
        // 删除指定cookie
        unset($_COOKIE[$name]);
    }

    /**
     * Cookie清空
     * @param string|null $prefix cookie前缀
     * @return mixed
     */
    public static function clear($prefix = null)
    {
        // 清除指定前缀的所有cookie
        if (empty($_COOKIE)) {
            return;
        }
        !isset(self::$init) && self::init();
        // 要删除的cookie前缀，不指定则删除config设置的指定前缀
        $config = self::$config;
        $prefix = !is_null($prefix) ? $prefix : $config['prefix'];
        if ($prefix) {
            // 如果前缀为空字符串将不作处理直接返回
            foreach ($_COOKIE as $key => $val) {
                if (0 === strpos($key, $prefix)) {
                    if ($config['setcookie']) {
                        setcookie($key, '', $_SERVER['REQUEST_TIME'] - 3600, $config['path'], $config['domain'], $config['secure'], $config['httponly']);
                    }
                    unset($_COOKIE[$key]);
                }
            }
        }
        return;
    }
    //array_walk_recursive($value, 'self::jsonFormatProtect', 'encode')
    private static function jsonFormatProtect(&$val, $key, $type = 'encode')
    {
        if (!empty($val) && true !== $val) {
            $val = 'decode' == $type ? urldecode($val) : urlencode($val);
        }
    }

}
