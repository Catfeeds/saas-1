<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    // 七牛token
    Route::get('/get_qi_niu_token', 'PlatformsController@token');
    // 登录
    Route::post('logins', 'LoginsController@Logins');

    //退出登录
    Route::get('logout', 'LoginsController@logout');

    Route::group(['middleware' => 'apiAuth:admin'], function () {

        Route::resource('admins','AdminsController');
        Route::get('test', 'LoginsController@test');

        // 公司管理
        Route::resource('company', 'CompanyController');

        // 获取城市下的区域
        Route::get('get_area','CompanyController@getArea');

        // 启用
        Route::get('enables','CompanyController@enable');

        // 禁用
        Route::get('disables','CompanyController@disable');

        // 所有的楼座下拉数据
        Route::get('building_blocks_all', 'PlatformsController@buildingBlocksSelect');

        // 平台房源管理
        Route::resource('platforms','PlatformsController');

    });





});