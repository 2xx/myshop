<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;

class LoginController extends Controller
{
    // 登录页面
    public function login()
    {
        return view('login/login');
    }

    // 验证
    public function dologin(Request $req)
    {
        // 接收数据进行验证
        $uname = $req->post('uname');
        $upwd  = $req->post('upwd',null,'md5');
        $code  = $req->post('code');

        // 判断验证码
        if( !captcha_check($code) ){
            return $this->error('验证码不对,请重新添写','/admin/login');
        };

        // 查库验证
        $user = User::where('uname','=',$uname)->where('upwd','=',$upwd)->find();
        
        if ($user) {

            // 设置变量$adminFlag=true 表示登录成功 这个变量可以在任何一个页面中使用
            session('adminFlag', true);   // $_SESSION['adminFlag'] = true;

            // 保存当前登录的用户信息
            session('adminUserInfo', $user); // $_SESSION['adminUserInfo'] = $user;

            return $this->success('登录成功','/admin/default');
        } else {
            return $this->error('账号或密码错误','/admin/login');
        }


    }

    // 退出
    public function logout()
    {
        session('adminFlag', NULL);
        return $this->success('正在退出...','/admin/login');
    }


    // 默认页
    public function default()
    {
        return view('common/default');
    }
}
