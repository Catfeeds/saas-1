<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Company', 'prefix' => 'company'], function () {

    Route::resource('company', 'CompanyController');

    Route::resource('areas', 'AreasController');

    Route::resource('storefronts', 'StorefrontsController');

    Route::resource('groups', 'GroupsController');


    //用户管理
    Route::resource('users','UsersController');
});