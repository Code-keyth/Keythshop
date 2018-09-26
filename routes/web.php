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

Route::group(['middleware' => ['AdminLogin']], function () {
    Route::get('/admin', 'AdminController@index');
    Route::get('/admin/welcome', 'AdminController@welcome');
    Route::get('/admin/goods', 'AdminController@goods');
    Route::get('/ajax/get_up_state', 'AjaxController@get_up_state');
    Route::get('/admin/goods_add', 'AdminController@goods_add');
    Route::post('/admin/goods_add_c', 'AdminController@goods_add_c');
    Route::get('/admin/goods_type', 'AdminController@goods_type');
    Route::get('/admin/goods_type_add', 'AdminController@goods_type_add');
    Route::post('/admin/goods_type_add_c', 'AdminController@goods_type_add_c');
    Route::get('/ajax/get_type_posterity', 'AjaxController@get_type_posterity');
    Route::get('/admin/goods_evaluate', 'AdminController@goods_evaluate');
    Route::get('/admin/goods_activity', 'AdminController@goods_activity');
    Route::post('/ajax/post_up_activity', 'AjaxController@post_up_activity');
    Route::get('/admin/goods_recycle', 'AdminController@goods_recycle');

    Route::get('/admin/article', 'AdminController@article');
    Route::get('/admin/article_add', 'AdminController@article_add');
    Route::post('/admin/article_add_c', 'AdminController@article_add_c');

    Route::get('/admin/article_type', 'AdminController@article_type');
    Route::post('/ajax/add_article_type', 'AjaxController@add_article_type');


    Route::get('/admin/article_recycle', 'AdminController@article_recycle');



    Route::get('/admin/order', 'AdminController@order');
    Route::get('/admin/order_delivery', 'AdminController@order_delivery');
    Route::get('/admin/order_retreat', 'AdminController@order_retreat');

    Route::get('/admin/admin_user', 'AdminController@admin_user');
    Route::get('/admin/admin_role', 'AdminController@admin_role');
    Route::get('/admin/admin_powe', 'AdminController@admin_powe');
    Route::get('/admin/admin_log', 'AdminController@admin_log');

    Route::get('/admin/member', 'AdminController@member');
    Route::get('/admin/member_account', 'AdminController@member_account');
    Route::get('/admin/member_order', 'AdminController@member_order');
    Route::get('/admin/member_message', 'AdminController@member_message');

    Route::get('/admin/article', 'AdminController@article');
    Route::get('/admin/article_typ', 'AdminController@article_typ');
    Route::get('/admin/article_recycle', 'AdminController@article_recycle');

    Route::get('/admin/setup_shop', 'AdminController@setup_shop');
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


