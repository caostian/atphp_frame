<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/2
 * Time: 15:38
 */


namespace atphp;
use atphp\exception\ExceptionExt;

class Controller
{

    protected $assign = [];
    private $controller;
    private $action;

    /**
     * 为模板对象赋值
     */
    public function assign($name, $data = null)
    {
        if (is_array($name)) {
            $this->assign = array_merge($this->assign, $name);
        } else if (is_object($name)) {
            foreach ($name as $key => $val)
                $this->assign[$name] = $val;
        } else {
            $this->assign[$name] = $data;
        }
    }

    /**
     * 用于在控制器中加载一个模板文件
     */
    public function display($file = null)
    {
        //定位到当前的视图文件夹
        $view_dir = MODULE_PATH . "view/";

        $template_suffix = trim(Config::get("template_suffix")," \.");



        if($file==null){
            $file = $this->getController() ."/".$this->action.".".$template_suffix;
        }

        if (is_file($view_dir . $file)) {
            \Twig_Autoloader::register();
            $loader = new \Twig_Loader_Filesystem($view_dir);
            $twig = new \Twig_Environment($loader, [
                'cache' => RUNTIME_PATH . 'twig_cache',
                'debug' => DEBUG,
            ]);

            $template = $twig->loadTemplate($file);
            $template->display($this->assign ? $this->assign : []);
        } else {
            if (DEBUG) {
                throw new ExceptionExt($file . '是一个不存在的模板文件');
            } else {
                show404();
            }
        }
    }

    public function getController()
    {
        return ucfirst($this->controller);
    }

    public function setAction(){
        return $this->action;
    }

    public function __set($name,$value){
        $this->$name = $value;
    }


    protected function isAjax() {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
            if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
                return true;
        }
        if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')]))
            // 判断Ajax方式提交
            return true;
        return false;
    }

}