<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CustomersRequest;
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
        $res = $service->getList($request);
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
    public function edit(Customer $customer)
    {
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

    // 更改客源类型(公私盘)
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
    
}
