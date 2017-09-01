<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/2
 * Time: 15:55
 * 数组操作
 * 由于直接类名Array ,会有冲突,所以类名用Array
 *
 */

namespace  atphp\util;
class ArrayUtil
{

    /**
     * 数组递归处理, 用指定的方法递归的处理数组的每个元素, 多维数组也会处理
     *
     * @param array $array 待处理的数组
     * @param string $fun 处理的方法名
     * @return array 处理后的数组
     */
    public static function map($array, $fun)
    {
        foreach ($array as $key => $item) {
            $array[$key] = is_array($item) ? self::map($item, $fun) : call_user_func($fun, $item);
        }
        return $array;
    }

    /**
     * 重构数组, 用指的定的键值重新组成新的数组元素
     *
     * @param array $array 待处理的数组
     * @param string $keyField 键值
     * @param array /string $valueField 新数组包含的数据
     * @return array 处理后的数组
     */
    public static function format($array, $keyField = null, $valueField = null)
    {
        $newArray = array();
        foreach ($array as $key => $value) {
            $index = !is_null($keyField) ? $value[$keyField] : $key;
            if (is_null($valueField)) {
                $newArray[$index] = $value;
            } elseif (is_array($valueField)) {
                reset($valueField);
                foreach ($valueField as $valueKey => $valueItem) {
                    $newArray[$index][$valueItem] = $value[$valueItem];
                }
            } else {
                $newArray[$index] = $value[$valueField];
            }
        }
        return $newArray;
    }

    /**
     * 随机抽取数组元素
     *
     * @param array $array 来源数组
     * @return mixed 随机元素
     */
    public static function rand($array)
    {
        $index = array_rand($array);
        return $array[$index];
    }

    /**
     * 随机抽取多维数组元素
     *
     * @param array $array 来源数组
     * @param integer $count 抽取的纪录数
     * @return mixed
     */
    public static function randMulti($array, $count = 1)
    {
        $count = min($count, count($array));
        $index = array_rand($array, $count);
        if (is_array($index)) {
            foreach ($index as $i) {
                $ret[] = $array[$i];
            }
        } else {
            $ret = array($array[$index]);
        }
        return $ret;
    }

    /**
     * 数组排序
     *
     * @param array $array 待排序的数组
     * @param string $keyFields 依照排序的键名
     * @param string $sortTypes 排序方式, asc: 升序, desc: 降序
     * @return array 排序后的数组
     */
    public static function sort($array, $keyFields = 0, $sortTypes = 'asc')
    {
        // $sortType —— 'asc': 升序 'desc': 降序

        $valueArray = array();
        $newArray = array();

        $keyField = is_array($keyFields) ? current($keyFields) : $keyFields;
        $sortType = is_array($sortTypes) ? current($sortTypes) : $sortTypes;

        foreach ($array as $key => $item) {
            $valueArray[$key] = $item[$keyField];
        }

        $sortFunc = strtolower($sortType) == 'desc' ? 'arsort' : 'asort';
        $sortFunc($valueArray);

        $lastItem = null;
        $i = 0;
        foreach ($valueArray as $key => $item) {
            if (!is_null($lastItem) && $array[$key][$keyField] != $lastItem[$keyField])
                $i++;
            $newArray[$i][$key] = $array[$key];
            $lastItem = $array[$key];
        }
        if (is_array($keyFields)) {
            if (array_shift($keyFields)) {
                if (is_array($sortTypes)) {
                    array_shift($sortTypes);
                }
                foreach ($newArray as $key => $item) {
                    if (count($item) > 1) {
                        $newArray[$key] = self::sort($item, $keyFields, $sortTypes);
                    }
                }
                reset($newArray);
            }
        }

        $retArray = array();
        foreach ($newArray as $key => $item) {
            foreach ($item as $sKey => $sItem) {
                $retArray[$sKey] = $array[$sKey];
            }
        }
        return $retArray;
    }

    /**
     * 重置数组索引
     *
     * @param array $array 待处理的数组
     * @return array 处理后的数组
     */
    public static function reindex($array)
    {
        $newArray = array();
        foreach ($array as $key => $item) {
            $newArray[] = $item;
        }
        return $newArray;
    }

    /**
     * 连接数组并忽略空键值
     *
     * @param string $char 连接的字符
     * @param array $array 连接的数组
     * @return string 连接后的字符串
     */
    public static function join($char, $array)
    {
        foreach ($array as $key => $item) {
            if (strval($item) == '') {
                unset($array[$key]);
            }
        }
        $str = join($char, $array);
        return $str;
    }

    /**
     * 过滤数组中不存在于某范围的键
     *
     * @param array $array 待过滤的数组
     * @param array $arrayKeys 保留的键值
     * @return array 过滤后的数组
     */
    public static function filter($array, $arrayKeys)
    {
        foreach ($array as $key => $item) {
            if (!in_array($key, $arrayKeys)) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * 字符串或数组是否包含某个值
     *
     * @param string /array $array 待检查的字符串或者数组
     * @param string $string 需要检查的字符串
     * @param string $splitStr 分隔字符串的字符
     * @return mixed
     */
    public static function inArray($array, $string, $splitStr = ',')
    {
        if (!is_array($array)) {
            $array = explode($splitStr, $array);
        }
        return (in_array($string, $array));
    }

    /**
     * 将二维数组中某一列构成一个新的数组
     *
     * @param array $array 来源数组
     * @param string $keyField 键名
     * @return array 新数组
     */
    public static function cols($array, $keyField)
    {
        $array_cols = array();
        foreach ($array as $key => $item) {
            $array_cols[] = $item[$keyField];
        }
        return $array_cols;
    }

    /**
     * 序列转换数组
     *
     * @param string $strSerial 来源序列
     * @param string $strSplitMain 序列一级分隔符
     * @param string $strSplitSub 序列二级分隔符
     * @return array
     */
    public static function serialToArray($strSerial, $strSplitMain = '|', $strSplitSub = ':')
    {
        $arrResult = array();
        if ($strSerial) {
            $arrRand = explode($strSplitMain, $strSerial);
            foreach ($arrRand as $key => $item) {
                $arrItem = explode($strSplitSub, $item);
                $arrItem[0] = str_replace(array("\n", "\r"), '', $arrItem[0]);
                $arrResult[$arrItem[0]] = $arrItem[1];
            }
        }
        return $arrResult;
    }

    /**
     * 数组转换序列
     *
     * @param array $array 来源数组
     * @param string $strSplitMain 序列一级分隔符
     * @param string $strSplitSub 序列二级分隔符
     * @return string
     */
    public static function arrayToSerial($array, $strSplitMain = '|', $strSplitSub = ':')
    {
        foreach ($array as $key => $item) {
            $array[$key] = $key . $strSplitSub . $item;
        }
        $strSerial = join($strSplitMain, $array);
        return $strSerial;
    }

    /**
     * 对象转化为数组
     *
     * @param object $obj 来源对象
     * @return array
     */
    public static function objectToArray($obj)
    {
        if (is_object($obj)) {
            $obj = (array)$obj;
        }
        if (is_array($obj)) {
            $obj = Ext_Array::map($obj, 'Ext_Array::objectToArray');
        }
        return $obj;
    }

    /**
     * 二维数组排序
     *
     * @param $arr :数据
     * @param $keys :排序的健值
     * @param $type :升序/降序
     *
     * @return array
     */
    public static function multiArraySort($arr, $keys, $type = "asc")
    {
        if (!is_array($arr)) {
            return false;
        }
        $keysvalue = array();
        foreach ($arr as $key => $val) {
            $keysvalue[$key] = $val[$keys];
        }
        if ($type == "asc") {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $key => $vals) {
            $keysort[$key] = $key;
        }
        $new_array = array();
        foreach ($keysort as $key => $val) {
            $new_array[$key] = $arr[$val];
        }
        return $new_array;
    }

    /**
     * 递归的去除数组中的空元素
     *
     * @param array $array 待处理的数组
     * @return array
     */
    public static function trimEmpty($array)
    {
        foreach ($array as $key => $item) {
            if (is_array($item)) {
                $array[$key] = self::trimEmpty($item);
            } elseif (!$item) {
                unset($array[$key]);
            }
        }
        reset($array);
        return $array;
    }

    /**
     * @Summary 根据数组中概率值随机抽取key
     * @Param $array Array key => 概率 对应的数组
     * $mul Int default 1000 随机值倍数
     * @Return 随机key
     */
    public static function randByProbability($array, $mul = 1000)
    {
        $max = array_sum($array) * $mul;
        $rand = round(mt_rand(0, $max));
        $next = 0;
        $last = 0;
        foreach ($array as $key => $val) {
            $val = $val * $mul;
            $next += $val;
            if ($rand >= $last && $rand <= $next) {
                $res = $key;
                break;
            }
            $last = $next;
        }
        return $res;
    }

    /**
     * 键值是否存在数组中
     * */
    public static function keyInArray($key, $array)
    {
        return array_key_exists($key, $array);
    }


    /**
     * @param array $array
     * @param string $case CASE_LOWER CASE_UPPER
     * @param bool $flag_rec 是否递归处理,默认是递归的
     * @return array
     */
    //多维数组,把键名统一该大写和小写 ----array_change_key_case 系统自带的只能改以为数组
    public static function changeArrayCase($array,$case=CASE_UPPER,$flag_rec = true){
       $array =   array_change_key_case($array,$case);
        if($flag_rec){
            foreach ($array  as $key=> $value){
                if(is_array($value)){
                   $array[$key] = self::changeArrayCase($array[$key],$case,$flag_rec);
                }
            }
        }
        return $array;
    }
}


