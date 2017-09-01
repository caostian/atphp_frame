<?php

/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/7/31
 * Time: 16:17
 */

namespace atphp;

use atphp\exception\ExceptionExt;
use atphp\util\LogUtil;

class ATPHP
{
    /**
     * model用于存放已经加载的model模型,下次加载时直接返回
     */
    public $model;
    /**
     * 视图赋值
     */
    public $assign;
    /**
     * 框架启动方法,完成了两件事情
     * 1.加载route解析当前URL
     * 2.找到对应的控制以及方法,并运行
     */
    public static function run()
    {
        $request = new \atphp\Router();
        $ctrlClass = '\\'.MODULE_NAME.'\controller\\' . ucfirst($request->controller)."Controller";

        $action = $request->action;
        $ctrlFile = MODULE_PATH . 'controller/' . ucfirst($request->controller) . 'Controller.php';

        if (is_file($ctrlFile)) {
            include_once $ctrlFile;
           if(!method_exists($ctrlClass,$action)){
               if (DEBUG) {
                   throw new ExceptionExt("请求方法不存在,{$ctrlClass}->{$action}" . ' : not exits');
               } else {
                   show404();
               }
           }
        } else {
            //写入日志 Module:ExecuteAutoTagedAction uri:/ExecuteAutoTagedAction
            if(Config::get("LogUtil_status")){
                LogUtil::write("Module:".$request->controller ."/".$request->action,LogUtil::ERR,LogUtil::FILE,"http_404.LogUtil");
            }

            if (DEBUG) {
                throw new ExceptionExt($ctrlClass . ' : not exits');
            } else {
                show404();
            }
        }
        $ctrl = new $ctrlClass();
        $ctrl->controller = $request->controller;
        $ctrl->action = $request->action;


        //如果开启restful,那么加载方法时带上请求类型
        if (Config::get('OPEN_RESTFUL')) {
            $action = strtolower($request->method()) . ucfirst($action);
        }
        $ctrl->$action();
    }
}