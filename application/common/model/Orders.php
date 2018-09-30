<?php

namespace app\common\model;

use think\Model;

class Orders extends Model
{
    protected $table = 'shop_orders';
    protected $pk    = 'oid';

    public function details()
    {
    	return $this->hasMany('Details', 'orders_id', 'oid');
    }
}
