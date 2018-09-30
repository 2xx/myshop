<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use app\common\model\Goods;
use app\common\model\Cate;
use think\Image;

class GoodsController extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index(Request $req)
    {
        // 获取数据
        $condition = [];

        // 要查找的类别ID
        if ($cid = $req->get('cid')) {
            $arr_cid = Cate::where('path','like',"%,$cid,%")->column('cid');
            $arr_cid[] = $cid;
            $condition[] = ['cate_cid', 'in', $arr_cid];
        }


        // 要查找的商品名称
        if ($gname = $req->get('gname')) {
            $condition[] = ['gname', 'like', $gname];
        }

        // 要查找的最低价格
        if ($min_price = $req->get('minPrice')) {
            $condition[] = ['price', '>=', $min_price];
        }

        // 要查找的最高价格
        if ($max_price = $req->get('maxPrice')) {
            $condition[] = ['price', '<=', $max_price];
        }


        $goods = Goods::with('cate')->where( $condition )->paginate(5)->appends($req->get());

        // 遍历显示
        return view('goods/index',['goods'=>$goods]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        // 获取所有类别信息
        $cates = Cate::orderRaw('concat(path,cid,",")')->select();

        return view('goods/create', ['cates'=>$cates]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $req)
    {
        $data = $req->post();
        $data['create_at'] = time();

        // 接收表单提交的上传文件
        $file = $req->file('smallimg');

        // 把上传文件从临时目录移动到指定位置, 返回上传信息
        $info = $file->move(  config('save_path')  );

        // 从上传信息中获取 上传文件的文件名
        $fileName = $info->getSaveName();

        // 把上传文件名存到$data数组中, 之后插入到数据库
        $data['gpic'] = $fileName;

        //  20180821\eae750d5534cfc630cf0df3fc44ef666.jpg
        //  20180821/sm_eae750d5534cfc630cf0df3fc44ef666.jpg
        $thumb_name = str_replace('\\', '/sm_', $fileName); // 生成缩略图文件名

        // 生成缩略图
        Image::open($file)->thumb(150,150)->save(  config('save_path').$thumb_name  );

        try{
            Goods::create($data,true);
        }catch(\Exception $e){
            return $this->error('添加商品失败');
        }
        return $this->success('添加商品成功','/admin/goods/create');
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
        // 获取商品原有信息
        $goods = Goods::get($id);

        // 获取所有类别信息
        $cates = Cate::orderRaw('concat(path,cid,",")')->select();

        // 显示到表单
        return view('goods/edit',['goods'=>$goods,'cates'=>$cates]);
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

        $file = $req->file('smallimg');

        // if ($_FILES['smallimg']['error'] != 4)

        if ($file) {

            $info = $file->move( config('save_path') );

            $fileName = $info->getSaveName();

            $data['gpic'] = $fileName;
        }

        Goods::update($data,['gid'=>$id],true);
        return $this->success('....','/admin/goods/index');
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


    // 上架
    public function up($id, $status=2)
    {
        $data['status'] = $status;
        Goods::update($data, ['gid'=>$id], true);
        return redirect('/admin/goods/index');
    }

    // 下架
    public function down($id)
    {
        // $data['status'] = 3;
        // Goods::update($data, ['gid'=>$id], true);
        // return redirect('/admin/goods/index');
        return $this->up($id,3);

        // 调用任意模块中的任意控制器中的任意方法
        // return action('admin/GoodsController/up',['id'=>$id,'status'=>3]);
    }
}
