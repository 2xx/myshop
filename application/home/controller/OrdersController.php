<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\Orders;
use app\common\model\Details;

class OrdersController extends Controller
{
    
    // 获取收货信息
    public function getinfo()
    {
        return view('orders/getinfo');
    }


    // 显示结算页
    public function commit(Request $req)
    {
        // 接收并且保存收货信息
        session('orders.rec', $req->post('rec'));   // 收货人
        session('orders.tel', $req->post('tel'));   // 联系电话
        session('orders.addr',$req->post('addr'));  // 收货地址
        session('orders.umsg',$req->post('umsg'));  // 买家留言

        return view('orders/commit');
    }
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
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
    public function save(Request $req)
    {
        $data = session('orders');

        $data['oid'] = date('YmdHis').mt_rand(1000,9999);
        $data['user_id'] = session('homeUserInfo.uid');
        $data['status'] = 1;
        $data['create_at'] = time();

        try{
            $orders = Orders::create($data, true);
            $orders -> details() -> saveAll( session('cart') );
        }catch(\Exception $e){
            return $this->error('生成订单失败','/cart/index');
        }

        // 清空session数据
        session('orders',null);
        session('cart',null);

        return $this->success('成功生成订单', '/');

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
        //
    }


    // 用户订单查看(我的订单)
    public function userorders()
    {
        // 获取当前用户的ID
        $uid = session('homeUserInfo.uid');

        // 根据用户ID,查询出他所有的订单   按时间降序
        $orders = Orders::where('user_id', '=', $uid)->order('create_at','desc')->select();

        return view('orders/userorders', ['orders'=>$orders]);
    }

    // 确认收货
    public function commitRec($id)
    {
        Orders::update(['status'=>'3'], ['oid'=>$id], true);
        return redirect('/orders/user');
    }
}
