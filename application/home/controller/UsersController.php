<?php

namespace app\home\controller;

use think\Controller;
use think\Request;
use think\Image;
use app\common\model\User;

class UsersController extends Controller
{

    protected $batchValidate = true; // 批量验证
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
    }

    
    // 用户注册-页面
    public function create()
    {
        return view('user/create');
    }

    // 用户注册-动作
    public function save(Request $req)
    {
        $data = $req->post();  // 接收数据

        // 验证
        $res = $this->validate($data, '\\app\\home\\validate\\User');

        // 有错误带相应错误信息跳转
        if ($res !== true) {
            halt($res);
            return redirect('/signup')->with($res);
        }

        try{
            User::create($data,true); // 添加
        } catch(\Exception $e) {
            return $this->error('添加失败');
        }

        return $this->success('添加成功','/login');
    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read()
    {
        return view('user/read', ['user'=>session('homeUserInfo')]);
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

    // 接收个人中心中修改后的内容
    public function update(Request $req, $id)
    {
        $data = $req->post();

        $file = $req->file('uface');

        if ($file) {
            
            $info = $file->move(config('save_path'));
            $fileName = $info->getSaveName();

            // 缩略图名称
            $thumb_name = str_replace('\\', '/sm_', $fileName);

            // 生成缩略图
            Image::open($file)->thumb(150, 150)->save(config('save_path').$thumb_name);

            $data['uface'] = $fileName;
        }

        try{
            User::update($data, ['uid'=>$id], true);
        } catch(\Exception $e) {
            return $this->error('修改失败');
        }

        session('homeUserInfo', User::get($id));

        return $this->success('修改成功', '/user/center');
        

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
