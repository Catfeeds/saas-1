<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {
    //手机密码登录
    Route::resource('logins', 'LoginsController');
    //微信扫码登录
    Route::post('wechat_logins', 'LoginsController@wechatLogins');
    //绑定微信后直接登录
    Route::post('banding_wechat', 'LoginsController@bandingWechat');
    //退出登录
    Route::get('logout', 'LoginsController@logout');
});