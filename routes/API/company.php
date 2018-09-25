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
    Route::get('freeze','UsersController@freeze');
    // 离职
    Route::get('resignation','UsersController@resignation');
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

    Route::post('update_pic', 'UsersController@updatePic');


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

    // 通过角色等级获取下拉数据
    Route::get('get_level_list','CompanyFrameworksController@getLevelList');
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
    Route::post('update_img','HousesController@updateImg');
    // 置顶
    Route::get('set_top','HousesController@setTop');
    // 取消置顶
    Route::get('cancel_top','HousesController@cancelTop');
    // 转移房源
    Route::get('transfer_house','HousesController@transferHouse');
    // 转为公盘
    Route::get('change_to_public','HousesController@changeToPublic');
    // 转为私盘
    Route::get('switch_to_private','HousesController@switchToPrivate');
    // 转为无效
    Route::get('turned_invalid','HousesController@turnedInvalid');
    // 转为有效
    Route::get('turn_effective','HousesController@turnEffective');
    // 修改证件图片
    Route::post('relevant_proves','HousesController@relevantProves');

    // 看房方式
    Route::post('see_house_way','HousesController@seeHouseWay');
    // 获取业主信息
    Route::get('get_owner_info','HousesController@getOwnerInfo');
    // 获取门牌号
    Route::get('get_house_number','HousesController@getHouseNumber');


    // 写跟进
    Route::resource('tracks','TracksController');
     // 写提醒
    Route::resource('reminds','RemindsController');
    //带看登记
    Route::resource('visits', 'VisitsController');

    //获取房源动态
    Route::resource('house_operation_records', 'HouseOperationRecordsController');

    //  房源共享
    Route::post('share_house', 'HousesController@shareHouse');

    // 下架共享房源
    Route::post('un_share', 'HousesController@unShare');

    /*
    |--------------------------------------------------------------------------
    | 共享房源
    |--------------------------------------------------------------------------
    */

    // 共享房源
    Route::resource('shares', 'SharesController');

    // 公司共享
    Route::get('company_shares', 'SharesController@companyShares');

    // 公司共享房源详情
    Route::get('company_show', 'SharesController@companyShow');

    /*
    |--------------------------------------------------------------------------
    | 客源管理
    |--------------------------------------------------------------------------
    */

    Route::resource('customers', 'CustomersController');

    // 获取正常状态客源下拉数据
    Route::get('normal_customer','CustomersController@normalCustomer');

    // 客源转为无效
    Route::post('invalid', 'CustomersController@invalid');

    // 更改客源类型(公私客)
    Route::post('update_guest', 'CustomersController@updateGuest');

    // 转移客源,变更人员
    Route::post('transfer', 'CustomersController@transfer');

    // 获取客源信息
    Route::get('get_customers_info','CustomersController@getCustomersInfo');

    // 获取楼盘,楼座关联基础数据
    Route::get('building_blocks_select','CustomersController@buildingBlocksSelect');

    // 获取客源全部动态
    Route::resource('customer_operation_records', 'CustomerOperationRecordsController');

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
    // 获取公司所在区域
    Route::get('company_area','HousesController@companyArea');

    // 问题反馈
    Route::resource('feed_backs','FeedBacksController');

    /*
    |--------------------------------------------------------------------------
    | 业务管理
    |--------------------------------------------------------------------------
    */
    Route::resource('business_manage','BusinessManageController');

    // 新增房源
    Route::get('get_add_house', 'BusinessManageController@getHouse');

    // 新增客源
    Route::get('get_add_customer', 'BusinessManageController@getCustomer');

    // 房源跟进
    Route::get('get_house_track', 'BusinessManageController@getHouseTrack');

    // 客源跟进
    Route::get('get_customer_track', 'BusinessManageController@getCustomerTrack');

    // 房源带看
    Route::get('get_house_visit', 'BusinessManageController@getHouseVisit');

    // 客源带看
    Route::get('get_customer_visit','BusinessManageController@getCustomerVisit');

    // 提交钥匙
    Route::get('get_see_house_way','BusinessManageController@getSeeHouseWay');

    // 上传图片
    Route::get('get_record_img','BusinessManageController@getRecordImg');

    // 查看房号
    Route::get('get_record_house_number','BusinessManageController@getRecordHouseNumber');

    // 查看业主信息
    Route::get('get_record_ownerinfo','BusinessManageController@getRecordOwnerInfo');

});
