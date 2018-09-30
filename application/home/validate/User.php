<?php

namespace app\home\validate;

use think\Validate;

class User extends Validate
{
    /**
     * 定义验证规则
     * 格式：'字段名'	=>	['规则1','规则2'...]
     *
     * @var array
     */	
	protected $rule = [
        'uname' => 'require|unique:shop_users',
        'upwd'  => 'require',
        'reupwd'=> 'require|confirm:upwd',
        'authcode'=>'require|captcha'
    ];
    
   
    protected $message = [
        'uname.require' => '账号不能为空',
        'uname.unique'   => '账号已存在',
        'upwd.require'  => '密码不能为空',
        'reupwd.require'=> '确认密码不能为空',
        'reupwd.confirm' => '两次密码不一致',
        'authcode.require'=>'验证码必须填',
        'authcode.captcha' =>'验证码错误',
    ];
}
