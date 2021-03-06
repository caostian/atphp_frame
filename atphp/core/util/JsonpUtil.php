<?php

namespace atphp\util;

use atphp\Request;

class JsonpUtil
{
    // 输出参数
    protected  static $options = [
        'var_jsonp_handler'     => 'callback',
        'default_jsonp_handler' => 'jsonpReturn',
        'json_encode_param'     => JSON_UNESCAPED_UNICODE,
    ];

    protected static $contentType = 'application/javascript';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     * @throws \Exception
     */
    public static function output($data)
    {
        try {
            // 返回JSON数据格式到客户端 包含状态信息 [当url_common_param为false时是无法获取到$_GET的数据的，故使用Request来获取<xiaobo.sun@qq.com>]
            $var_jsonp_handler = Request::getString("var_jsonp_handler");
            $handler           = !empty($var_jsonp_handler) ? $var_jsonp_handler :self::$options['default_jsonp_handler'];

            $data = json_encode($data, self::$options['json_encode_param']);

            if ($data === false) {
                throw new \InvalidArgumentException(json_last_error_msg());
            }

            $data = $handler . '(' . $data . ');';
            return $data;
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                throw $e->getPrevious();
            }
            throw $e;
        }
    }

}
