<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Company', 'prefix' => 'company'], function () {

    Route::resource('company', 'CompanyController');

    /*
    |--------------------------------------------------------------------------
    | 用户管理
    |--------------------------------------------------------------------------
    */
    Route::resource('users','UsersController');
    // 冻结
    Route::get('freeze/{guid}','UsersController@freeze');
    // 离职
    Route::get('resignation/{guid}','UsersController@resignation');

    /*
    |--------------------------------------------------------------------------
    | 岗位管理
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | 组织架构
    |--------------------------------------------------------------------------
    */
    Route::resource('company_frameworks', 'CompanyFrameworksController');
    // 通过公司获取所有用户
    Route::get('adopt_company_get_user', 'CompanyFrameworksController@adoptCompanyGetUser');
    // 通过区域获取所有用户
    Route::get('adopt_area_get_user', 'CompanyFrameworksController@adoptAreaGetUser');
    // 通过门店/组获取所有用户
    Route::get('adopt_condition_get_user', 'CompanyFrameworksController@adoptConditionGetUser');
    // 通过用户名称获取用户
    Route::get('adopt_name_get_user', 'CompanyFrameworksController@adoptNameGetUser');

});