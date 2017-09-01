<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/29
 * Time: 10:59
 * 框架异常类
 */

namespace atphp\exception;

use Exception;

class ExceptionExt extends \Exception
{

    /**
     * @const integer 系统错误标识
     */
    const PHP_ERROR = 0;

    /**
     * @const integer 程序错误标识
     */
    const CODE_ERROR = -1;

    /**
     * @const integer 用户消息标识
     */
    const USER_ERROR = -2;
    /**
     * @const integer 数据库错误标识
     */
    const DB_ERROR = -3;


    /**
     * ExceptionExt constructor.
     * @param string $message 这个前面加@ 表示敏感的报错,会自动屏蔽掉这种详细信息,以程序异常文本显示给用户
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }


    //捕获异常
    public function getError()
    {
        $trace = $this->getTrace();

        $types = array(
            self::CODE_ERROR => '程序错误',
            self::PHP_ERROR => '系统错误',
            self::USER_ERROR => '用户错误',
            self::DB_ERROR => '数据库错误',
        );


        $type = isset($types[$this->getCode()]) ? $types[$this->getCode()] : 'UNKNOW_ERROR';

        $tmpArr = array();
        $index = 0;

        foreach ($trace as $value) {
            $tmpStr = '';
            if (isset($value['line'])) $tmpStr .= "[Line: " . $value['line'] . "]";
            if (isset($value['file'])) $tmpStr .= $value['file'];
            $tmpStr .= "(";
            if (isset($value['class'])) $tmpStr .= $value['class'];
            if (isset($value['type'])) $tmpStr .= $value['type'];
            if (isset($value['function'])) $tmpStr .= $value['function'];
            if (isset($value['args'])) $tmpStr .= "(" . $this->getArgsInfo($value['args']) . ")";
            $tmpStr .= ")";
            $tmpArr[] = $tmpStr;
            $index++;
        }
        $error['code'] = $this->getCode();
        $error['message'] = $this->getMessage();
        $error['type'] = $type;
        $error['file'] = $this->getFile();
        $error['line'] = $this->getLine();
        $error['trace'] = $tmpArr;
        // $error['traceArr'] = $trace;
        return $error;
    }

    /**
     * 获取参数信息
     * @param array | object | string $args 参数
     * @return string 参数信息
     */
    public function getArgsInfo($args)
    {
        $tmpArr = array();
        foreach ($args as $value) {
            if (is_object($value)) {
                $tmpArr[] = "Object(" . get_class($value) . ")";
            } elseif (is_array($value)) {
                $tmpArr[] = 'Array';
            } else {
                $tmpArr[] = $value;
            }
        }
        return implode(", ", $tmpArr);
    }


    function __toString()
    {
        return __CLASS__ . ':[' . $this->code . ']:' . $this->message . '\n';
    }


}