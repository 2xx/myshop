<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Orders;

class OrdersController extends Controller
{
    /*
      功能:显示所有订单
    
    */
    public function index(Request $req)
    {
        $condition = [];

        if ($status = $req->get('status')) {
            $condition[] = ['status', '=', $status];
        }

        if ($oid = $req->get('oid')) {
            $condition[] = ['oid', 'like', "%$oid%"];
        }

        $orders = Orders::where($condition)->paginate(3)->appends($req->get());
        return view('orders/index', ['orders'=>$orders]);
    }



    /*
      显示订单详情
    */
    public function read($id)
    {
        $orders = Orders::get($id);

        // echo '<pre>';
        // print_r($orders->details);die;

        return view('orders/read', ['orders'=>$orders]);
    }

    // 修改订单主表信息
    public function edit($id)
    {
        $orders = Orders::get($id);
        return view('orders/edit', ['orders'=>$orders]);
    }

    // 修改订单信息
    public function update(Request $req, $id)
    {
        $data = $req->post();
        try{
            Orders::update($data, ['oid'=>$id], true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }

        return $this->success('修改成功','/admin/orders/index');
        
    }

    
}
