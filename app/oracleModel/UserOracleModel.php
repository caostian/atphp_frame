<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/9/13
 * Time: 11:50
 */

namespace oracleModel;


class UserOracleModel extends BaseOracleModel
{

    public function __construct($oracleConfigKey = null)
    {
        parent::__construct($oracleConfigKey);

    }

    public function getUseInfo($v_id = '', $v_uid = '')
    {

        //PROCEDURE "p_t_user_info"(v_id in NUMBER,v_uid in VARCHAR2,cur out sys_refcursor)
        $sql = 'BEGIN game_user."p_t_user_info"(:v_id,:v_uid,:cur); END;';
        error_reporting(0);
        $stid = @oci_parse($this->hander, $sql);
        @oci_bind_by_name($stid, ":v_id", $v_id);
        @oci_bind_by_name($stid, ":v_uid", $v_uid);
        $cur = @oci_new_cursor($this->hander);
        @oci_bind_by_name($stid, ":cur", $cur, -1, OCI_B_CURSOR);
        @oci_execute($stid);
        @oci_execute($cur);
        $info = oci_fetch_assoc($cur);
        if ($info) {
            $info['uid'] = icovGbkToUtf8($info['uid']);
            $info['name'] = icovGbkToUtf8($info['name']);
        }

        //这里直接触发错误
        $this->checkError($stid, $cur);
        oci_free_statement($stid);
        oci_free_statement($cur);


        return $info;
    }


}