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


    // 用户管理
    Route::resource('users','UsersController');
    // 冻结
    Route::get('freeze/{guid}','UsersController@freeze');
    // 离职
    Route::get('resignation/{guid}','UsersController@resignation');
    // 岗位管理
    Route::resource('quarters','QuartersController');
    // 修改岗位名称
    Route::post('update_role_name','QuartersController@updateRoleName');
    // 修改岗位级别
    Route::post('update_role_level','QuartersController@updateRoleLevel');
    // 修改岗位权限
    Route::post('update_role_permission', 'QuartersController@updateRolePermission');

    // 微信确认
    Route::get('confirm_wechat', 'UsersController@confirmWechat');
    // 微信换绑
    Route::post('update_wechat', 'UsersController@updateWechat');

    Route::resource('role_has_permission_list', 'RoleHasPermissionController');


    //组织架构
    Route::get('company_frameworks', 'CompanyFrameworksController');

    //新增片区
    Route::get('new_area','CompanyFrameworksController@newArea');

    //新增门店
    Route::get('new_store','CompanyFrameworksController@newStore');

    //新增分组
    Route::get('new_group','CompanyFrameworksController@newGroup');

});