<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::post('logins', 'LoginsController@Logins');

    //公司管理
//    Route::resource('company', 'CompanyController');

    Route::group(['middleware' => 'apiAuth:admin'], function () {

        Route::resource('admins','AdminsController');
        Route::get('test', 'LoginsController@test');

        // 公司管理
        Route::resource('company', 'CompanyController');
        // 获取城市下的区域
        Route::get('get_area','CompanyController@getArea');
        // 修改状态
        Route::get('update_status','CompanyController@enabledState');
    });





});