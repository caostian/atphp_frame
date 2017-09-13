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
use atphp\exception\ExceptionExt;
use oracleModel\UserOracleModel;
use service\UserAuthScoketService;


/*****
 * mycat 的一些测试
 *
 */
class Test2Controller extends Controller
{

    public function index()
    {

        $model = new Model();

        //跨分片join,目前感觉有bug,limit 分页 和排序功能都有问题
//        $result = $model->getRows('/*!mycat:catlet=io.mycat.catlets.ShareJoin */   SELECT * FROM  t_user u LEFT JOIN  `t_servier` ser on u.user_id = ser.use_id order by ser.ser_id DESC LIMIT 0,2');
//
//        dump($result);
//
//        //过分片查询
//        $result = $model->table("t_user")->order(["user_id"=>"desc","user_name"=>"asc"])->find();
//        echo  $model->getLastSql();
//
//        dump($result);

        //插入
//        $result = $model->table("t_user")->insert(array(
//            "user_name" => "php测试",
//            "user_money" => 100,
//        ));

        //  dump($result);


    }

    public function test1()
    {
        $userSocketService = new UserAuthScoketService();
        $userSocketService->sendBroad("woshishui");
    }

    public function test2()
    {
        try {
            $useOracleModel = new UserOracleModel();
            $info = $useOracleModel->getUseInfo();
            dump($info);
        } catch (ExceptionExt $e) {
           // echo "ggg";
            dump($e);
        }


    }
}