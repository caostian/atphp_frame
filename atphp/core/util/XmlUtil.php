<?php
namespace atphp\util;

use atphp\exception\ExceptionExt;

class XmlUtil
{
    // 输出参数
    protected static $options = [
        // 根节点名
        'root_node' => 'atian',
        // 根节点属性
        'root_attr' => '',
        //数字索引的子节点名
        'item_node' => 'item',
        // 数字索引子节点key转换的属性名
        'item_key' => 'id',
        // 数据编码
        'encoding' => 'utf-8',
    ];

    protected static $contentType = 'text/xml';

    /**
     * 处理数据
     * @access protected
     * @param mixed $data 要处理的数据
     * @return mixed
     */
    public static function encode(array $data)
    {
        // XML数据转换
        return self::xmlEncode($data, self::$options['root_node'], self::$options['item_node'], self::$options['root_attr'], self::$options['item_key'], self::$options['encoding']);
    }


    /**
     * 生成简洁一点的XML
     * @param $data
     * @return string
     * @throws ExceptionExt
     */
    public static function encodeSimple($data)
    {
        if (!is_array($data) || count($data) <= 0) {
            throw new ExceptionExt("array data error!");
        }

        $xml = "<xml>";
        foreach ($data as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }


    public static function decode($xml)
    {
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
    }


    /**
     * XML编码
     * @param mixed $data 数据
     * @param string $root 根节点名
     * @param string $item 数字索引的子节点名
     * @param string $attr 根节点属性
     * @param string $id 数字索引子节点key转换的属性名
     * @param string $encoding 数据编码
     * @return string
     */
    protected static function xmlEncode($data, $root, $item, $attr, $id, $encoding)
    {
        if (is_array($attr)) {
            $array = [];
            foreach ($attr as $key => $value) {
                $array[] = "{$key}=\"{$value}\"";
            }
            $attr = implode(' ', $array);
        }
        $attr = trim($attr);
        $attr = empty($attr) ? '' : " {$attr}";
        $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
        $xml .= "<{$root}{$attr}>";
        $xml .= self::dataToXml($data, $item, $id);
        $xml .= "</{$root}>";
        return $xml;
    }

    /**
     * 数据XML编码
     * @param mixed $data 数据
     * @param string $item 数字索引时的节点名称
     * @param string $id 数字索引key转换为的属性名
     * @return string
     */
    protected static function dataToXml($data, $item, $id)
    {
        $xml = $attr = '';
        foreach ($data as $key => $val) {
            if (is_numeric($key)) {
                $id && $attr = " {$id}=\"{$key}\"";
                $key = $item;
            }
            $xml .= "<{$key}{$attr}>";
            if ((is_array($val) || is_object($val))) {
                $xml .= self::dataToXml($val, $item, $id);
            } else if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
            $xml .= "</{$key}>";
        }
        return $xml;
    }


}
