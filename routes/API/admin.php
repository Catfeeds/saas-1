<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Admin', 'prefix' => 'admin'], function () {

    Route::post('logins', 'LoginsController@Logins');

    Route::group(['middleware' => 'apiAuth:admin'], function () {

        Route::get('test', 'LoginsController@test');

        //公司管理
        Route::resource('company', 'CompanyController');

    });





});