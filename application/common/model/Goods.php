<?php

namespace app\common\model;

use think\Model;

class Goods extends Model
{
    protected $table = 'shop_goods';
    protected $pk    = 'gid';

    // 在商品模型建立与类别模型的关联
    public function cate()
    {
    	return $this->belongsTo('Cate', 'cate_cid', 'cid');
    }

    // 获取器  当代码中尝试获取 商品的smgpic这个属性时,触发该方法
    public function getSmgpicAttr()
    {
    	  //   20180821\645461780887e5422ffe1862792df123.jpg
    	  //   20180821/sm_645461780887e5422ffe1862792df123.jpg
    	return  str_replace('\\', '/sm_', $this->gpic);
    }
}
