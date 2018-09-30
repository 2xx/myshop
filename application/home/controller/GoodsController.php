<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;
use app\common\model\Goods;

class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index($id=0)
    {
        $condition = [];

        // 如果传了类别ID
        if (!empty($id)) {
            // 先根据类别ID查出所有子类ID,以一给数据形式返回
            $arr_cid = Cate::where('path','like',"%,$id,%")->column('cid'); // [13,14,15,16]   []
            $arr_cid[] = $id;   // [13,14,15,16,1]                                             [14]

            // 形成查询商品的条件
            $condition[] = ['cate_cid','in', $arr_cid ];
            // select * from shop_goods where cate_cid in (14);
        }

        // 如果传了$_GET['gname']商品名称
        if (!empty($_GET['gname'])) {
            $condition[] = ['gname','like', "%{$_GET['gname']}%"];
        }

        // 不显示下架商品
        $condition[] = ['status','<', 3];

        $goods = Goods::where( $condition )->select();

        // select * from shop_goods where cate_cid in (52,53,54,55,56,57,58,59,60)

        return view('goods/index',['goods'=>$goods]);

    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //
    }

    // 商品详情
    public function read($id)
    {
        // 获取信息
        $goods_info = Goods::get($id);

        // 显示到页面
        return view('goods/read',['goods'=>$goods_info]);
    }

    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
