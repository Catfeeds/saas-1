<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Access;
use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CustomersRequest;
use App\Models\Company;
use App\Models\Customer;
use App\Services\CustomersService;

class CustomersController extends APIBaseController
{
    // 客源列表
    public function index
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        // 公客范围
        $public = Access::adoptPermissionGetUser('public_customer_show');

        // 私客范围
        $private = Access::adoptPermissionGetUser('private_customer_show');

        //  公私客范围都为空, 则直接返回
        if (!$public['status'] && !$private['status']) return $this->sendError('无客源列表权限');

        if (!$public['status'] && $private['status']) $guardian_person = $private['message'];

        if ($public['status'] && !$private['status']) $guardian_person = $public['message'];

        if ($public['status'] && $private['status']) $guardian_person = array_merge($private['message'], $public['message']);

        $guardian_person  = array_unique($guardian_person);

        $res = $service->getList($request, $guardian_person);
        return $this->sendResponse($res, '客源列表获取成功');
    }

    // 添加客源
    public function store
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        $res = $service->addCustomer($request);
        return $this->sendResponse($res, '客源添加成功');
    }

    public function show
    (
        $guid,
        CustomersService $service
    )
    {
        $res = $service->getCustomerInfo($guid);
        return $this->sendResponse($res, '客源详情获取成功');
    }

    // 客源修改之前原始数据
    public function edit
    (
        Customer $customer,
        CustomersService $service
    )
    {
        $customer->permisson = $service->getPermission($customer);
        return $this->sendResponse($customer, '修改之前原始数据');
    }

    // 更新客源
    public function update
    (
        Customer $customer,
        CustomersService $service,
        CustomersRequest $request
    )
    {
        $res = $service->updateCustomer($customer, $request);
        if (!$res) return $this->sendError('客源修改失败');
        return $this->sendResponse($res, '客源修改成功');
    }

    // 删除客源
    public function destroy(Customer $customer)
    {
        $res = $customer->delete();
        return $this->sendResponse($res, '客源删除成功');
    }

    // 客源转为无效
    public function invalid
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        $res = $service->invalid($request);
        if (!$res) return $this->sendError('设置失败');
        return $this->sendResponse($res, '设置成功');
    }

    // 更改客源类型(公私客)
    public function updateGuest
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        $res = $service->updateGuest($request);
        if (!$res) return $this->sendError('设置失败');
        return $this->sendResponse($res, '设置成功');
    }

    // 转移客源,变更人员
    public function transfer
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        $res = $service->transfer($request);
        if (!$res) return $this->sendError('设置失败');
        return $this->sendResponse($res, '设置成功');
    }

    // 获取所有商圈
    public function allBlock()
    {
        $res =  curl(config('hosts.building').'/api/all_block','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有商圈成功');
    }

    // 获取所有楼盘
    public function allBuilding()
    {
        $res = curl(config('hosts.building').'/api/all_building','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有楼盘成功');
    }

    // 获取正常状态客源下拉数据
    public function normalCustomer
    (
        CustomersService $service
    )
    {
        $res = $service->normalCustomer();
        return $this->sendResponse($res,'正常状态客源下拉数据获取成功');
    }

    // 获取客源信息
    public function getCustomersInfo
    (
        CustomersRequest $request,
        CustomersService $service
    )
    {
        $res = $service->getCustomersInfo($request);
        if (!$res) return $this->sendError('获取客源信息失败');
        return $this->sendResponse($res,'获取客源信息成功');
    }

    // 获取楼座下拉数据
    public function buildingBlocksSelect()
    {
        // 获取登录人公司所在的城市
        $cityName = Company::find(Common::user()->company_guid)->city_name;
        $res = curl(config('hosts.building').'/api/get_building_block?city_name='.$cityName,'GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }
}
