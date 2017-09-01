<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/30
 * Time: 14:42
 */

namespace atphp\db;


use atphp\Config;
use atphp\exception\ExceptionExt;

class Model
{
    protected $table;
    protected static $db_instance = []; //连接的实例,每个数据库连接只用实例化一次,单例
    // protected static $model_instance = []; //各种模型的实例,单例
    public $db = null;

    /**
     * 这里我只是数据库连接用了静态变量报错,单独的模型,没有这么干
     * Model constructor.
     * @param null $data_config
     * @throws ExceptionExt
     */
    public function __construct($data_config = null)
    {
        $config = Config::get("mysql");

        if ($data_config == null) {
            if (count($config) != count($config, true)) {
                //二维数组
                $config = array_values($config)[0];//如果是二维数组,默认取第一个
            }
        } else {
            //如果不等于空
            $config = $data_config;
        }
        if (!$config) {
            throw  new ExceptionExt("数据库配置文件读取失败", ExceptionExt::DB_ERROR);
        }

        $key_sign = "mysql:" . $config["db_host"] . "->" . $config['db_name'];

        if (!isset(self::$db_instance[$key_sign])) {
            try {
                self::$db_instance[$key_sign] = new Mysqli($config["db_host"], $config["db_port"], $config["db_user"], $config["db_pwd"], $config["db_name"]);

            } catch (\Exception $e) {
                throw new ExceptionExt("数据库连接失败,或配置出错!");
            }
        }

        $this->db = self::$db_instance[$key_sign];
    }


    /**
     * 查询数据,多条数据
     * @return $this
     */
    public function select()
    {
        return $this->db->select();
    }

    /**
     * 查询单条数据
     * @return $this
     */
    public function find()
    {
        $this->db->find();
        return $this;
    }

    public function getCurrentDb()
    {
        return $this->db->getCurrentDb();

    }


    public function getRows($sql)
    {
        return $this->db->table($this->table)->getRows($sql);

    }

    public function getRow($sql)
    {
        return $this->db->getRow($sql);
    }

    public function insertId()
    {
        return $this->db->insertId();

    }

    public function affectRows()
    {
        return $this->db->affectRows();
    }

    public function getLastSql()
    {
        return $this->db->getLastSql();

    }

    public function getAllSql()
    {
        return $this->db->getAllSql();
    }

    public function update($data)
    {
        return $this->db->update($data);
    }


    public function delete()
    {
        return $this->db->delete();
    }

    public function insert($data)
    {
        return $this->db->insert($data);
    }


    public function replace($data)
    {
        return $this->db->replace($data);
    }

    public function begin()
    {
        return $this->db->begin();
    }

    public function rollBack()
    {
        return $this->db->rollBack();
    }

    public function commit()
    {
        $this->db->commit();
        return $this;
    }

    public function getTransactionNum()
    {
        $this->db->getTransactionNum();
        return $this;
    }


    public function order($order)
    {
        $this->db->order($order);
        return $this;
    }


    public function limit($offset)
    {
        $this->db->limit($offset);
        return $this;
    }


    public function offset($offset)
    {
        $this->db->offset($offset);
        return $this;
    }

    public function field($field)
    {
        $this->db->field($field);
        return $this;
    }

    public function group($group)
    {
        $this->db->group($group);
        return $this;
    }


    public function where($where)
    {
        $this->db->where($where);
        return $this;
    }


    public function table($table = '')
    {
        if ($table != '') {
            $this->table = $table;
        }
        $this->db->table($this->table);

        return $this;
    }





    //    /**
//     * 这里是一个单例,实例化一次---我放弃了,,因为............没法索引到方法..要你何用
//     * @param null $data_config 多数据库时候,配置数据库连接的名称,为空的话,默认第一个数据库连接, 如果只是单数据库,不需要传值
//     * @return mixed
//     */
//    public static function getInstance($data_config = null)
//    {
//        $className = get_called_class();
//
////        if (!isset(self::$model_instance[$className])) {
////            self::$model_instance[$className] = new $className($data_config);
////        }
////        return self::$model_instance[$className];
//
//
//        return new UserModel();
//    }

}
