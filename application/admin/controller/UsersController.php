<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\User;
use think\facade\Session;

class UsersController extends Controller
{
    
    protected $batchValidate = true; // 允许批量验证

    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        echo Session::get('name');
        // 定义一个数组保存条件
        $condition = [];

        // 如果有性别条件
        if (!empty($_GET['sex'])) {
            $condition[] =  ['sex', '=', $_GET['sex']]; //  select * from shpo_users where sex='{$_GET['sex']}';
        }

        // 如果有账号条件
        if (!empty($_GET['uname'])) {
            $condition[] = ['uname', 'like', "%{$_GET['uname']}%"];
        }

        // 获取数据
        $users = User::where( $condition ) -> paginate(3)->appends($_GET);

        // halt($users);

        // 遍历显示
        return view('user/index',['users'=>$users]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 把user目录中create.html文件拿过来显示
        return view('user/create');
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        // 接收数据
        $data = $request->post();

       
        $res = $this->validate($data, 'app\admin\validate\User');

        if ($res !== true) {
            // halt($res);
            // return $this->error($res);
            return redirect('/admin/user/create')->with($res);
        }

        // 追加创建时间
        $data['create_at'] = time();

        // halt($data);

        // 保存到数据库
        try{
            $user = User::create($data, true);
        }catch(\Exception $e){
            return $this->error('添加用户失败','/admin/user/create');
        }

        return $this->success('添加用户成功','/admin/user/create');
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
        // 获取指定ID的信息
        $user = User::get($id);

        // dump($user);

        // 显示到表单
        return view('user/edit',['user'=>$user]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $req, $id)
    {
        $data = $req->post();

        try{
            User::update($data, ['uid'=>$id], true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }

        return $this->success('修改成功','/admin/user/index');

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $row = User::destroy($id);

        if ($row) {
            return $this->success('删除成功','/admin/user/index');
        } else {
            return $this->error('删除失败');
        }
    }


    // 修改自己的密码
    public function chgpwd()
    {
        return view('user/chgpwd');
    }

    // 修改自己的密码-动作
    public function dochgpwd(Request $req)
    {
        if ( empty($req->post('new_upwd')) || empty($req->post('reupwd'))  ) {
            return $this->error('密码不能为空');
        }

        if ( $req->post('new_upwd') !== $req->post('reupwd')) {
            return $this->error('两次密码不一致');
        }

        if ( $req->post('upwd',null,'md5') !== session('adminUserInfo.upwd') ) {
            return $this->error('原有密码错误');
        }

        // echo $req->post('new_upwd',null,'md5'),'<br>';

        session('adminUserInfo.upwd', $req->post('new_upwd'));

        session('adminUserInfo')->save();

        return $this->success('密码修改成功!','/admin/default');

    }

}
