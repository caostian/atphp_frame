<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/11
 * Time: 15:52
 */

namespace home\controller;


use atphp\Controller;
use atphp\db\Model;


/*****
 * mycat 的一些测试
 *
 */

class Test2Controller extends Controller
{

    public function index()
    {

        $model = new Model();
        $result = $model->getRows('/*!mycat:catlet=io.mycat.catlets.ShareJoin */   SELECT * FROM  t_user u LEFT JOIN  `t_servier` ser on u.user_id = ser.use_id order by ser.ser_id DESC LIMIT 0,2');

        dump($result);


        $result = $model->table("t_user")->order(["user_id"=>"desc","user_name"=>"asc"])->select();
        echo  $model->getLastSql();

        dump($result);

    }
}