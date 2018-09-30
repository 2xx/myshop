<?php

namespace app\admin\validate;

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
        'uname' => 'require|max:6|unique:shop_users',
        'upwd'  => 'require|min:6',
        'reupwd'=> 'require|min:6|confirm:upwd'
    ];
    
    /**
     * 定义错误信息
     * 格式：'字段名.规则名'	=>	'错误信息'
     *
     * @var array
     */	
    protected $message = [
        'uname.require' => '你丫连名字都不填啊!',
        'uname.max'     => '不要以为名字长,什么就都都长,6个就够了',
        'uname.unique'  => '该账号已经存在,不能注册了',
        'upwd.require'  => '密码不能为空',
        'upwd.min'      => '密码最少6位',
        'reupwd.require'=> '确认密码不能为空', 
        'reupwd.min'=> '确认密码不能少于6位', 
        'reupwd.confirm'=> '两次密码不一致', 
    ];
}
