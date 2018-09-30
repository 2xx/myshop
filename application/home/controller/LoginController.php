<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use app\common\model\User;

class LoginController extends Controller
{
    
    // 前台登录-页面
    public function login()
    {
        return view('login/login');
    }

    // 前台登录-动作
    public function dologin(Request $req)
    {
        $uname = $req->post('uname',null,'trim');
        $upwd  = $req->post('upwd',null,'md5');

        $user = User::where('uname','=',$uname)->where('upwd','=',$upwd)->find();

        if ($user) {
           session('homeFlag', true);      // 表示登录成功
           session('homeUserInfo', $user); // 保存当前登录的用户信息

           $uri = empty(  session('back_uri')  ) ? '/' : session('back_uri');

           session('back_uri',NULL);

           return  $this->success('登录成功', $uri);
        } else {
           return $this->error('登录失败','/login');
        }

    }

    // 退出登录
    public function logout()
    {
        session('homeFlag',NULL);
        return $this->success('正在退出...', '/');
    }


}
