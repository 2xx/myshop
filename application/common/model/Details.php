<?php

namespace app\common\model;

use think\Model;

class Details extends Model
{
    protected $table = 'shop_details';
    protected $pk    = 'did';

    // 建立与订单主表的关联
    public function orders()
    {
    	return $this->belongsTo('Orders', 'orders_id', 'oid');
    }

    // 建立与商品表的关联
    public function goods()
    {
    	return $this->belongsTo('Goods', 'gid', 'gid');
    }

    // 设置模型事件
    static public function init()
    {
        self::event('after_insert', function($details){
            Goods::get($details->gid)->setDec('stock', $details->cnt);
            Goods::get($details->gid)->setInc('salecnt', $details->cnt);
                                     

            // update shop_goods set stock=stock-$details->cnt where gid=$details->gid
        });
    }
}
