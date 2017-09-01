<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/31
 * Time: 11:14
 */
namespace home\controller;

use atphp\Controller;
use atphp\db\Model;
use atphp\exception\ExceptionExt;
use atphp\Request;
use atphp\util\LogUtil;


//我自己测试这玩的...哈哈哈 ,,不要当真
class TestController extends Controller
{

    public $clean = false;

    public function test3()
    {

        //这个方法,PHP结束线程的时候,会自动调用这个回调,所以,还是有一点作用的.....
        register_shutdown_function(function () {
            if (!$this->clean) {
                LogUtil::write("这里没有执行成功", LogUtil::ERR, LogUtil::FILE, "redigster_shutdown.log");
            } else {
                echo "<br/>success<br/>";
            }
//            return false;
        });

//        sleep(31);//超时并没有什么软用,不会执行这个回调
//       for($i = 0;$i<1000000000000;$i++){
//           echo $i;
//       }

        sleep(31);

        echo $this->clean;
    }

    public function test2()
    {
        try {
            $dbh = new \PDO('mysql:host=localhost;dbname=test', 'root', '123456');
            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);//有错误就跑出异常
            $dbh->setAttribute(\PDO::ATTR_EMULATE_PREPARES, false); //禁用prepared statements的仿真效果(防SQL注入)
//            $sth = $dbh->prepare('select * from user id = :id', array(\PDO::ATTR_CURSOR => \PDO::CURSOR_FWDONLY));
            $sth = $dbh->prepare("select * from user  where id =:id");

            //第一种方法
            $sth->execute(array(":id" => 1));
            $res = $sth->fetchAll();
            dump($res);


            //第二种方式
            $id = 2;
            $sth->bindParam(":id", $id);

            $sth->execute();
            $res = $sth->fetchAll(\PDO::FETCH_ASSOC);
            dump($res);

        } catch (ExceptionExt $e) {
            dump($e->getMessage());
        }

    }


    public function test1()
    {
        try {
            $dbh = new \PDO('mysql:host=localhost;dbname=test', 'root', '123456');
            $result = $dbh->query('SELECT * from user where id=1001');

            foreach ($result as $row) {
                dump($row);
            }

            $dbh = null;
        } catch (\PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function test()
    {

        $user_model = new Model();
        // $name = "ati'an123";

        $name = Request::getString("name");
        $info = $user_model->table('user')->where(["name" => $name])->select();


        $res = $user_model->table("user")->where(["id"=>1001])->update(["name"=>"atian456"]);

        echo $res;

        echo $user_model->getLastSql();


        dump($user_model->getAllSql());

        $age = Request::getInteger("age");

        echo $age;


        dump($info);

        exit;
        //throw new ExceptionExt("你好");
        //测试PHP回调
        $this->func(function () {
            echo "nihao";
        });
    }

    public function func(Callable $callback = null)
    {
        if ($callback) {
            $callback();
        }
    }
}