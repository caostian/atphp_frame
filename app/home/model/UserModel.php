<?php
/**
 * Created by PhpStorm.
 * User: atian
 * Date: 2017/8/30
 * Time: 14:58
 */
namespace pay\Model;

use atphp\db\Model;

class UserModel extends Model
{

    public function __construct($data_config = null)
    {
        parent::__construct($data_config);

        $this->table("user");
    }

    public function getOne()
    {

    }
}