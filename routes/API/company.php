<?php
/**
 * Created by PhpStorm.
 * User: luozhen
 * Date: 2018/8/15
 * Time: 上午9:44
 */

Route::group(['namespace' => 'Company', 'prefix' => 'company'], function () {

    // 七牛token
    Route::get('/get_qi_niu_token', 'HousesController@token');

    //手机密码登录
    Route::resource('logins', 'LoginsController');
    //微信扫码登录
    Route::post('wechat_logins', 'LoginsController@wechatLogins');
    //绑定微信后直接登录
    Route::post('banding_wechat', 'LoginsController@bandingWechat');
    //退出登录
    Route::get('logout', 'LoginsController@logout');

    // 公司管理
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
    // 岗位下拉数据
    Route::get('get_all_quarters', 'UsersController@getAllQuarters');
    // 重置密码
    Route::get('reset_pwd', 'UsersController@resetPwd');
    // 获取所有人员
    Route::get('get_all_user', 'UsersController@getAllUser');

    //发送短信验证码
    Route::get('send_message/{tel}/{temp}', 'UsersController@sendMessage');
    //修改密码
    Route::post('update_pwd', 'UsersController@updatePwd');
    //获取员工个人信息
    Route::get('get_user', 'UsersController@getUser');

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
    // 通过公司/区域/门店/组获取所有用户
    Route::get('adopt_condition_get_user', 'CompanyFrameworksController@adoptConditionGetUser');
    // 通过用户名称获取用户
    Route::get('adopt_name_get_user', 'CompanyFrameworksController@adoptNameGetUser');
    // 根据条件获取所有区域/门店/组
    Route::get('get_all_basics_info', 'CompanyFrameworksController@getAllBasicsInfo');


    // 新增片区
    Route::post('add_area','CompanyFrameworksController@addArea');

    // 新增门店
    Route::post('add_storefront','CompanyFrameworksController@addStorefront');

    // 新增分组
    Route::post('add_group','CompanyFrameworksController@addGroup');


    // 通过门店获取分组
    Route::get('get_group', 'CompanyFrameworksController@getGroup');

    // 删除片区、门店、分组
    Route::get('delete', 'CompanyFrameworksController@delete');

    /*
    |--------------------------------------------------------------------------
    | 房源管理
    |--------------------------------------------------------------------------
    */
    Route::resource('houses', 'HousesController');
    // 变更人员
    Route::post('change_personnel', 'HousesController@changePersonnel');
    // 通过楼座,楼层获取房源
    Route::get('adopt_condition_get_house', 'HousesController@adoptConditionGetHouse');
    // 通过楼座，楼层获取房源
    Route::get('adopt_association_get_house', 'HousesController@adoptAssociationGetHouse');
    // 房号验证
    Route::get('house_number_validate', 'HousesController@houseNumberValidate');
    // 修改房源图片
    Route::get('update_img/{guid}','HousesController@updateImg');
    // 置顶
    Route::get('set_top/{guid}','HousesController@setTop');
    // 取消置顶
    Route::get('cancel_top/{guid}','HousesController@cancelTop');

    // 看房方式
    Route::post('see_house_way','HousesController@seeHouseWay');

    // 写跟进
    Route::resource('tracks','TracksController');
     // 写提醒
    Route::resource('reminds','RemindsController');
    /*
    |--------------------------------------------------------------------------
    | 基础数据
    |--------------------------------------------------------------------------
    */
    // 所有的楼座下拉数据
    Route::get('/building_blocks_all', 'HousesController@buildingBlocksSelect');
    // 获取所有下拉数据
    Route::get('get_all_select','HousesController@getAllSelect');
    // 获取所有商圈信息
    Route::get('all_block','CustomersController@allBlock');
    // 获取所有楼盘
    Route::get('all_building','CustomersController@allBuilding');

});
