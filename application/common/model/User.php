<?php

namespace app\common\model;

use think\Model;

class User extends Model
{
    protected $table = 'shop_users';
    protected $pk    = 'uid';

    // 当你对upwd字段修改值的时候,触发该方法
    public function setUpwdAttr($value)
    {
    	return md5($value);
    }
}
