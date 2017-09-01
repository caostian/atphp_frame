<?php

/**
 * MYSQLI 数据库驱动
 * Class Mysqli
 */

namespace atphp\db;

use atphp\Config;

class Mysqli extends Db
{
    /**
     * @var string 当前使用的库
     */
    private $_currentDb;
    /**
     * @var string 当前执行的SQL
     */
    private $_sql;

    /**
     * @var integer 影响条数
     */
    private $_affectRows = 0;

    /**
     * @var integer INSERT操作产生的ID
     */
    private $_insertId = 0;


    /**
     * 初使化连接
     *
     * @param string $host 服务器
     * @param string $port 端口
     * @param string $user 用户名
     * @param string $pass 密码
     * @param string $dbname 数据库名
     * @return void
     */
    public function __construct($host, $port, $user, $pass, $dbname = null)
    {
        if (!function_exists('mysqli_connect')) {
            $this->_showError('Mysqli support is disabled');
        }
        $this->_host = $host;
        $this->_port = $port;
        $this->_user = $user;
        $this->_pass = $pass;
        $this->_dbname = $dbname;
        $this->_charset = Config::get('db_charset');
    }

    /**
     * 连接数据库
     *
     * @return void
     */
    public function connect()
    {
        if (!$this->_link) {
            $mysqli = @mysqli_connect($this->_host, $this->_user, $this->_pass, '', $this->_port);
            if ($mysqli->connect_error) {
                $this->_showError('@' . $this->_host . "数据库服务器连接失败");
            }
            $this->_link = $mysqli;

            //自动提交
            //$mysqli->autocommit(1);
//            	mysqli_autocommit($this->_link, true);
            if ($this->_dbname) {
                $this->useDb($this->_dbname);
            }
        }
    }


    /**
     * 选择数据库
     *
     * @param string $dbname 数据库名
     * @return object 当前数据库对象
     */
    protected function usedb($dbname)
    {
        $flag = mysqli_select_db($this->_link, $dbname);
        if (!$flag) {
            $this->_showError('@' . $dbname . ': 数据库不存在或者无法使用');
        }
        $this->_currentDb = $dbname;
        mysqli_query($this->_link, "SET NAMES '{$this->_charset}'");
        return $this;
    }

    /**
     * 获取当前正在使用的库
     *
     * @return mixed
     */
    public function getCurrentDb()
    {
        return $this->_currentDb;
    }

    /**
     * 查询所有数据
     *
     * @param string $sql SQL语句
     * @param string $asKey 用做键值的字段名
     * @return array 查询结果
     */
    public function getRows($sql)
    {

        $query = $this->query($sql);
        $res = array();
        while ($value = mysqli_fetch_assoc($query)) {
            $res[] = $value;
        }
        return $res;
    }

    /**
     * 查询一条数据
     *
     * @param string $sql SQL语句
     * @param string $asKey 用做键值的字段名
     * @return array 查询结果
     */
    protected function getRow($sql)
    {
        if (stristr($sql, 'FOR UPDATE')) {
            $sql = str_ireplace('FOR UPDATE', 'LIMIT 1 FOR UPDATE', $sql);
        } else {
            $sql .= ' LIMIT 1';
        }
        $query = $this->query($sql);
        $res = array();
        while ($value = mysqli_fetch_assoc($query)) {
            $res[] = $value;
        }
        return $res;
    }

    /**
     * 执行SQL
     *
     * @param string $sql SQL语句
     * @return resource | bool | mixed
     */
    protected function query($sql)
    {
        if (!$this->_link) {
            $this->connect();
        }
        $sql = trim($sql);
        $this->_sql = $sql;
        $_runStartTime = microtime(true);
        $isInsert = (0 === stripos($sql, 'INSERT INTO'));
        $rs = mysqli_query($this->_link, $sql);
        $_runEndTime = microtime(true);

        // 纪录查询信息
        $this->_recordSqlQuery($sql, $_runStartTime, $_runEndTime);

        // 返回查询结果
        if ($rs) {
            $this->_affectRows = mysqli_affected_rows($this->_link);
            if ($isInsert) {
                $this->_insertId = mysqli_insert_id($this->_link);
            }
            return $rs;
        } else {
            $errno = mysqli_errno($this->_link);
            if ($this->_queryError) {
                $this->_showError("@SQL Query Error("
                    . mysqli_error($this->_link)
                    . " [$errno]): $sql");
            }
            return false;
        }
    }


    /**
     * 获取最后插入数据ID
     *
     * @return integer 最后插入数据ID
     */
    public function insertId()
    {
        return $this->_insertId;
    }

    /**
     * 获取影响行数
     *
     * @return integer 影响行数
     */
    public function affectRows()
    {
        return $this->_affectRows;
    }

    /**
     * 关闭数据库连接
     *
     * @return void
     */
    public function close()
    {
        if ($this->_link) {
            $rs = mysqli_close($this->_link);
            if ($rs) {
                $this->_link = null;
            }
        }
    }

    /**
     * 获取最后执行的SQL
     *
     * @param string $args
     * @return string 最后执行的SQL
     */
    public function getLastSql()
    {
        return $this->_sql;
    }


}