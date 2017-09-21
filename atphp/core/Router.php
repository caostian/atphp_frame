<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 16:22
 * 路由解析功能
 */
namespace atphp;

class Router
{
    public $controller;
    public $action;
    public $path;
    public $route;

    public function __construct()
    {
        $route = Config::get('route'); //加载整个路由配置文件

        if (isset($_SERVER['REQUEST_URI'])) {
            $pathStr = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['REQUEST_URI']);

            //丢掉?以及后面的参数
            $path = explode('?', $pathStr);
            //去掉多余的分隔符
            $path = explode('/', trim($path[0], '/'));

            if (isset($path[0]) && $path[0]) {
                $this->controller = $path[0]; //第一个分割的算是一个控制器
            } else {
                $this->controller =Config::get("default_controller");
            }


            unset($path[0]);//但索引是不会改变的
            //检测是否包含路由缩写
            if (isset($route[$this->controller])) {
                //配置的短域名路由
                $this->action = $route[$this->controller][1];
                $this->controller = $route[$this->controller][0];
            } else {
                //这个表示方法名称

                if (isset($path[1]) && $path[1]) {
                    $have = strstr($path[1], '?', true);
                    if ($have) {
                        $this->action = $have;
                    } else {
                        $this->action = $path[1];
                    }

                } else {
                    $this->action = Config::get("default_action");
                }
                unset($path[1]);
            }

            $this->path = array_merge($path); //这个会自动重新排列索引,很好的技巧啊

            //这里也可以模拟pathInfo模式,还可以有这种思路,不错,可以借鉴
            $pathLenth = count($path);
            $i = 0;
            while ($i < $pathLenth) {
                if (isset($this->path[$i + 1])) {
                    $_GET[$this->path[$i]] = $this->path[$i + 1];
                }
                $i = $i + 2;
            }
        } else {
            $this->controller =Config::get('default_controller');
            $this->action =  Config::get('default_action');
        }



    }

    public function urlVar($num, $default = false)
    {
        if (isset($this->path[$num])) {
            return $this->path[$num];
        } else {
            return $default;
        }
    }

    public function method()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}