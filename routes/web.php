<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

#统一异步请求由get_，post_开头

//后台模块
Route::group(['middleware' => ['AdminLogin']], function () {

    Route::get('/ajax/get_up_state', 'AjaxController@get_up_state');
    Route::post('/ajax/post_up_activity', 'AjaxController@post_up_activity');
    Route::post('/ajax/add_article_type', 'AjaxController@add_article_type');
    Route::get('/ajax/get_type_posterity', 'AjaxController@get_type_posterity');
    Route::get('/ajax/get_up_order_state', 'AjaxController@get_up_order_state');
    Route::get('/ajax/get_save_goods_spec', 'AjaxController@get_save_goods_spec');
    Route::get('/ajax/get_change_type_gain_spec', 'AjaxController@get_change_type_gain_spec');

    Route::post('/ajax/post_add_role', 'AjaxController@post_add_role');
    Route::post('/ajax/post_power_add', 'AjaxController@post_power_add');
    Route::post('/ajax/post_admin_role', 'AjaxController@post_admin_role');
    Route::get('/ajax/order_goto', 'AjaxController@order_goto');
    Route::get('/ajax/get_echarts', 'AjaxController@get_echarts');

    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/welcome', 'AdminController@welcome');
    Route::get('/admin/goods', 'AdminController@goods');
    Route::get('/admin/goods_add', 'AdminController@goods_add');
    Route::get('/admin/goods_spec', 'AdminController@goods_spec');

    Route::post('/admin/goods_add_c', 'AdminController@goods_add_c');
    Route::get('/admin/goods_type', 'AdminController@goods_type');
    Route::get('/admin/goods_type_add', 'AdminController@goods_type_add');
    Route::post('/admin/goods_type_add_c', 'AdminController@goods_type_add_c');
    Route::get('/admin/goods_evaluate', 'AdminController@goods_evaluate');
    Route::get('/admin/goods_activity', 'AdminController@goods_activity');
    Route::get('/admin/goods_recycle', 'AdminController@goods_recycle');
    Route::get('/admin/article', 'AdminController@article');
    Route::get('/admin/article_add', 'AdminController@article_add');
    Route::post('/admin/article_add_c', 'AdminController@article_add_c');
    Route::get('/admin/article_type', 'AdminController@article_type');

    #Route::get('/admin/article_recycle', 'AdminController@article_recycle');

    Route::get('/admin/order', 'AdminController@order');
    Route::get('/admin/order_info', 'AdminController@order_info');
    Route::get('/admin/order_delivery', 'AdminController@order_delivery');
    Route::get('/admin/order_retreat', 'AdminController@order_retreat');

    Route::get('/admin/admin_user', 'AdminController@admin_user');
    Route::get('/admin/admin_user_add', 'AdminController@admin_user_add');
    Route::post('/admin/admin_user_add_c', 'AdminController@admin_user_add_c');
    Route::get('/admin/admin_role', 'AdminController@admin_role');
    Route::get('/admin/admin_power', 'AdminController@admin_power');
    Route::get('/admin/admin_user_role', 'AdminController@admin_user_role');
    Route::get('/admin/admin_power_add', 'AdminController@admin_power_add');

    Route::get('/admin/admin_powe', 'AdminController@admin_powe');
    Route::get('/admin/admin_log', 'AdminController@admin_log');

    Route::get('/admin/member', 'AdminController@member');
    Route::get('/admin/member_add', 'AdminController@member_add');
    Route::post('/admin/member_add_c', 'AdminController@member_add_c');
    Route::get('/admin/member_account', 'AdminController@member_account');
    Route::get('/admin/member_order', 'AdminController@member_order');
    Route::get('/admin/member_message', 'AdminController@member_message');

    Route::get('/admin/article', 'AdminController@article');
    Route::get('/admin/article_typ', 'AdminController@article_typ');
    Route::get('/admin/article_recycle', 'AdminController@article_recycle');

    Route::get('/admin/setup_shop', 'AdminController@setup_shop');
    Route::get('/admin/setup_pay', 'AdminController@setup_pay');
    Route::get('/admin/setup_logistic', 'AdminController@setup_logistic');

    Route::post('/admin/setup_shop', 'AdminController@setup_shop_c');

    Route::get('/admin/setup_pay', 'AdminController@setup_pay');
    Route::get('/admin/setup_logistic', 'AdminController@setup_logistic');
    Route::get('/admin/setup_parent', 'AdminController@setup_parent');

    Route::get('/admin/statis_member', 'AdminController@statis_member');
    Route::get('/admin/statis_order', 'AdminController@statis_order');
    Route::get('/admin/statis_goods', 'AdminController@statis_goods');
    Route::post('/admin/upload_file', 'AjaxController@upload_file');
});
//登录模块
Route::get('/admin/login','LoginController@index');
Route::post('/admin/login','LoginController@login');
Route::get('/admin/logout','LoginController@logout');

Route::get('/index/login','LoginController@index_login');
Route::post('/index/login','LoginController@index_login_c');
Route::get('/index/logout','LoginController@index_logout');
Route::get('/index/register','LoginController@index_register');
Route::post('/index/register','LoginController@index_register_c');

//前台模块

Route::get('/','IndexController@index');

Route::get('/index','IndexController@index');
Route::get('/index/goods_list','IndexController@goods_list');
Route::get('/index/goods_info','IndexController@goods_info');

Route::group(['middleware' => ['IndexLogin']], function () {

    Route::get('/index/myinfo', 'IndexController@myinfo');
    Route::get('/index/myinfo_address', 'IndexController@myinfo_address');
    Route::get('/index/myorder', 'IndexController@myorder');
    Route::get('/index/myorder_info', 'IndexController@myorder_info');
    Route::post('/ajax/myinfo_address','AjaxController@myinfo_address');
    Route::post('/ajax/member_confirm_order','AjaxController@member_confirm_order');
    Route::post('/ajax/pay_order','AjaxController@pay_order');

    //取消订单
    Route::post('/ajax/cancel_order','AjaxController@cancel_order');
    //收货
    Route::get('/ajax/confirm_collect','AjaxController@confirm_collect');
    Route::get('/ajax/goods_collect','AjaxController@goods_collect');

});
Route::get('/index/mycart', 'IndexController@mycart');
Route::get('/index/settlement', 'IndexController@settlement');
Route::get('/ajax/get_select_goods_info', 'AjaxController@get_select_goods_info');
Route::post('/ajax/sendemail','AjaxController@sendemail');
Route::post('/ajax/addcart_good','AjaxController@addcart_good');




