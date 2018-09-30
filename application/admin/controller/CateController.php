<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Cate;

class CateController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        // 获取所有类别信息
        $cates = Cate::orderRaw('concat(path,cid,",")')->select();

        // 遍历显示到模板
        return view('cate/index',['cates'=>$cates]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create($id=0)
    {
        // 获取之前已有的类别信息
        $cates = Cate::orderRaw('concat(path,cid,",")')->select();

        return view('cate/create',['cates'=>$cates, 'id'=>$id]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $req)
    {
        // 接收的数据
        $data = $req->post();

        $pid = $req->post('pid'); // 获取pid

        if ($pid == 0) {
            $data['path'] = '0,';
        } else {
            $data['path'] = Cate::get($pid)->path."$pid,";
        }
        

        // 添加
        try{
            Cate::create($data, true);
        }catch(\Exception $e){
            return $this->error('添加类别失败');
        }

        return $this->success('添加类别成功','/admin/cate/create');

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
        // 获取原有信息
        $cate = Cate::get($id);

        // 显示到表单中
        return view('cate/edit',['cate'=>$cate]);
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
        // 接收数据
        $data = $req->post();

        // 执行更新
        try{
            Cate::update($data, ['cid'=>$id], true);
        }catch(\Exception $e){
            return $this->error('修改失败');
        }

        return $this->success('修改成功','/admin/cate/index');

    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {

        // 如果该类别下面有子类,就不能删除  find方法:有结果,返回一条结果,没结果返回NULL
        $cates = Cate::where('pid', '=', $id)->find();

        if ($cates) {
            return $this->error('该分类下面有子类别,所以不能删除');
        }


        // 如果该类别下面有对应的商品,也不能删除

        $row = Cate::destroy($id);

        if ($row) {
            return $this->success('删除成功~','/admin/cate/index');
        }

        return $this->error('删除失败');
    }
}
