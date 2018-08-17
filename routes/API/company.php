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
    // 角色管理
    Route::resource('roles','RolesController');
    // 修改角色名称
    Route::get('update_role_name','RolesController@updateRoleName');
    // 修改角色级别
    Route::get('update_role_level','RolesController@updateRoleLevel');
    // 修改角色权限
    Route::get('update_role_permission', 'RolesController@updateRolePermission');

    // 微信确认
    Route::get('confirm_wechat', 'UsersController@confirmWechat');
    // 微信换绑
    Route::post('update_wechat', 'UsersController@updateWechat');

    Route::resource('role_has_permission_list', 'RoleHasPermissionController');

});