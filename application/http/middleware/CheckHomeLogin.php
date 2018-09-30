<?php

namespace app\http\middleware;

class CheckHomeLogin
{
	use \traits\controller\Jump;  // 目的是使用 error 跳转方法

    public function handle($request, \Closure $next)
    {
    	if (empty(session('homeFlag'))){

    		// 把当前地址保存到session   
    		session('back_uri', $_SERVER['REQUEST_URI']);

    		// 跳转到登录页
    		return $this->error('请先去登录', '/login');
    	}

    	return $next($request);
    }
}
