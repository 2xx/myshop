<?php

namespace app\common\model;

use think\Model;

class Cate extends Model
{
    protected $table = 'shop_cate';
    protected $pk    = 'cid';

    static public function getCates($cates=[], $id=0)
    {
    	if (empty($cates)) {
    		$cates = self::select();
    	}

    	$arr = [];
    	foreach($cates as $k=>$v){
    		if ($v->pid==$id) {
    			$v->sub = self::getCates($cates, $v->cid);
    			$arr[] = $v;
    		}
    	}

    	return $arr;
    }
}
