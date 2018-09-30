<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;

class CartController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 获取购物车中所有信息
        $carts = session('cart');

        if (empty($carts)) {
            $carts = [];
        }

        $cnt = 0; // 总数量
        $sum = 0; // 总金额
        foreach($carts as $k=>$v){
            $cnt += $v->cnt;
            $sum += ($v->price * $v->cnt);
        }

        // 把总数量和总金额保存到session中,以后要使用
        session('orders.cnt',$cnt);
        session('orders.sum',$sum);

        // 遍历显示数据
        return view('cart/index',['carts'=>$carts, 'cnt'=>$cnt, 'sum'=>$sum]);
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
    public function save(Request $req, $id)
    {
        // 通过ID获取商品所有信息
        $goods_info = Goods::get($id);

        // 获取购买数量,在到商品对象中
        $goods_info->cnt = $req->post('cnt');


        // 把商品的信息存储到session中
        // session('下标',值);  session('cart.23', 值) ==> $_SESSION['cart'][23] = $goods_info;
        /*
           $_SESSION['cart'][A商品ID] = A商品信息
           $_SESSION['cart'][B商品ID] = B商品信息

           $_SESSION['cart']

           unset( $_SESSION['cart'][8]   )
        */
        session("cart.$id", $goods_info);

        return view('cart/save',['goods'=>$goods_info]);

    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
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
        session("cart.$id", NULL);
        return redirect('/cart/index');
    }


    // 减1操作
    public function dec($id)
    {
        session("cart.$id")->cnt--;

        // 如果数量小于1, 强行让它等于1
        if (session("cart.$id")->cnt < 1) {
            session("cart.$id")->cnt = 1;
        }

        return redirect('/cart/index');
    }


    // 加1操作
    public function inc($id)
    {
        session("cart.$id")->cnt++;
        return redirect('/cart/index');
    }
}
