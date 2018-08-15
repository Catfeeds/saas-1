<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    //登录
    Route::resource('logins', 'LoginsController');
    //退出登录
    Route::get('logout', 'LoginsController@logout');
});