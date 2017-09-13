<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 9:42
 * 我就是一个测试...淡定
 */

namespace service;


use atphp\Config;

class UserAuthScoketService
{
    private $hander = null;

    public function __construct()
    {
        $config = Config::get("socket")["user_auth"];
        $this->hander = SocketService::connect($config["host"], $config["port"]);
    }


    /**
     * 发公告
     */
    public function sendBroad($str)
    {

        $content = pack('L', 4 + 30 + 2 + 8 + 256);  // 4 byte
        $content .= $this->packHead(600); // 30 byte

        $content .= pack('S', 0);//无号短整数 2byte
        $content .= pack('L', 0);
        $content .= pack('L', 0);

        $str = iconv('utf-8', 'gb2312', $str);

        $content .= pack('a256', $str);
        $result = fwrite($this->hander, $content);

        dump($result);
        //关闭连接
        SocketService::close();
        if (false === $result) {
            return -2;
        }
        return 1;
    }


    //这里需要定义一个头部文件---c++后台服务端需要这个
    private function packHead($cmd, $s1 = 0, $s2 = 0, $s3 = 0, $s4 = 0)
    {
        $content = pack('L', $cmd); // uint_32_t cmd

        $content .= pack('l', 0);        //  int64_t cmdid
        $content .= pack('l', 0);

        $content .= pack('l', $s1);     //UUID
        $content .= pack('l', $s2);
        $content .= pack('l', $s3);
        $content .= pack('l', $s4);

        $content .= pack('c', 1); //need resp
        $content .= pack('c', 0); //byte align

        return $content;
    }

    //这里解包头部文件 ---c++后台服务端
    private function unPackHead($conn)
    {
        fread($conn, 4);       //接收数据流长度值存放大小

        //HEAD begin 30 byte
        fread($conn, 4);        //4字节CMD
        fread($conn, 4);
        fread($conn, 4);    //8字节CMDID
        fread($conn, 16);     //16字节UUID
        fread($conn, 1);
        fread($conn, 1);
        //HEAD end

        fread($conn, 4);  //4字节广播

        $arr = unpack('l', fread($conn, 4));  //4字节status状态，0成功，非0失败
        $ret = $arr[1];  //0：成功       28：1.读取数据包出错，2.数据库执行出错ORACLE报错
        return intval($ret);
    }
    //-----------------------新增头信息  end
}