<?php

namespace atphp\util;

class JsonUtil
{
    // 输出参数
    protected static $options = [
        'json_encode_param' => JSON_UNESCAPED_UNICODE,
    ];

    protected static $contentType = 'application/json';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     * @throws \Exception
     */
    public static function encode(array $data)
    {
        try {
            // 返回JSON数据格式到客户端 包含状态信息
            $data = json_encode($data, self::$options['json_encode_param']);

            if ($data === false) {
                throw new \InvalidArgumentException(json_last_error_msg());
            }

            return $data;
        } catch (\Exception $e) {
            if ($e->getPrevious()) {
                throw $e->getPrevious();
            }
            throw $e;
        }
    }

}
