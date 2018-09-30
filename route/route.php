<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------




/*前台路由*/

Route::rule('/','home/IndexController/index');  // 首页

/* 前台-用户 */
Route::rule('/signup','home/UsersController/create');  // 用户注册-页面
Route::rule('/dosignup', 'home/UsersController/save'); // 用户注册-动作
Route::rule('/user/center','home/UsersController/read')->middleware('CheckHomeLogin'); // 用户个人中心
Route::rule('/user/update/:id', 'home/UsersController/update')->middleware('CheckHomeLogin'); // 修改个人信息

/* 前台-商品 */
Route::rule('/goodslist/:id','home/GoodsController/index'); // 商品列表页
Route::rule('/goods/read/:id','home/GoodsController/read'); // 商品详情页

/* 前台-购物车 */
Route::group(['name'=>'/cart','prefix'=>'home/CartController/'],function(){
	Route::rule('save/:id', 'save');  // 加入购物车
	Route::rule('index',    'index'); // 浏览购物车
	Route::rule('dec/:id',  'dec');   // 减1操作
	Route::rule('inc/:id',  'inc');   // 加1操作
	Route::rule('delete/:id','delete');   // 删除购物车中的指定ID的商品
});


/* 前台-订单 */
Route::group(['name'=>'/orders','prefix'=>'home/OrdersController/'],function(){
	Route::rule('getinfo','getinfo'); // 获取订单收货信息
	Route::rule('commit', 'commit');  // 结算页
	Route::rule('save',   'save');    // 保存生成订单
	Route::rule('user',   'userorders');    // 某个用户的订单
	Route::rule('end/:id',    'commitRec'); // 用户-确认收货-行为
})->middleware('CheckHomeLogin');


/* 前台-登录,验证,退出 */
Route::rule('/login',  'home/LoginController/login');    // 前台登录-表单
Route::rule('/dologin','home/LoginController/dologin');  // 前台登录-验证
Route::rule('/logout', 'home/LoginController/logout');   // 前台退出登录




/* 后台-用户管理 */
Route::group([],function(){
	Route::rule('/admin/user/create',  'admin/UsersController/create');
	Route::rule('/admin/user/save',    'admin/UsersController/save');
	Route::rule('/admin/user/index',   'admin/UsersController/index');
	Route::rule('/admin/user/delete/:id','admin/UsersController/delete');
	Route::rule('/admin/user/edit/:id',  'admin/UsersController/edit');
	Route::rule('/admin/user/update/:id','admin/UsersController/update');
	Route::rule('/admin/user/chgpwd', 'admin/UsersController/chgpwd');     // 修改自己的密码-页面
	Route::rule('/admin/user/dochgpwd', 'admin/UsersController/dochgpwd'); // 修改自己的密码-动作
})->middleware('CheckAdminLogin');



/* 后台-类别管理 */
Route::group(['name'=>'/admin/cate','prefix'=>'admin/CateController/'], function(){
	Route::rule('create/[:id]', 'create', 'get');
	Route::rule('save',   'save',   'post');
	Route::rule('index',  'index',  'get');
	Route::rule('delete/:id','delete','get');
	Route::rule('edit/:id','edit', 'get');
	Route::rule('update/:id','update','post');
})->middleware('CheckAdminLogin');


/* 后台-商品管理 */
Route::group(['name'=>'/admin/goods', 'prefix'=>'admin/GoodsController/'],function(){
	Route::rule('create', 'create', 'get');  // 添加商品-表单
	Route::rule('save',   'save',  'post');  // 添加商品-存库
	Route::rule('index',  'index',  'get');  // 浏览商品
	Route::rule('edit/:id','edit',  'get');  // 修改商品-表单
	Route::rule('update/:id','update','post');  // 修改商品-接数据,存库
	Route::rule('up/:id',  'up',   'get');    // 上架
	Route::rule('down/:id','down', 'get');    // 下架
})->middleware('CheckAdminLogin');

/* 后台-订单管理 */
Route::rule('/admin/orders/index', 'admin/OrdersController/index');
Route::rule('/admin/orders/details/:id','admin/OrdersController/read');
Route::rule('/admin/orders/edit/:id','admin/OrdersController/edit');
Route::rule('/admin/orders/update/:id','admin/OrdersController/update');

/* 后台-登录 */
Route::group(['name'=>'/admin', 'prefix'=>'admin/LoginController/'],function(){
	Route::rule('login',  'login');    // 登录页面
	Route::rule('dologin','dologin');  // 验证登录
	Route::rule('logout', 'logout');   // 退出登录
	Route::rule('default','default')->middleware('CheckAdminLogin');
});



// Route::miss('/');

return [

];
