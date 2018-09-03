<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CustomersRequest;
use App\Models\Customer;
use App\Repositories\CustomersRepository;

class CustomersController extends APIBaseController
{
    // 客源类表
    public function index
    (
        CustomersRequest $request,
        CustomersRepository $repository
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res, '客源列表获取成功');
    }

    // 添加客源
    public function store
    (
        CustomersRequest $request,
        CustomersRepository $repository
    )
    {
        $res = $repository->addCustomer($request);
        return $this->sendResponse($res, '客源添加成功');
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
        CustomersRepository $repository,
        CustomersRequest $request
    )
    {
        $res = $repository->updateCustomer($customer, $request);
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
        $guid,
        CustomersRequest $request,
        CustomersRepository $repository
    )
    {
        $res = $repository->invalid($guid, $request);
        if (!$res) return $this->sendError('设置失败');
        return $this->sendResponse($res, '设置成功');
    }

    // 更改客源类型(公私盘)
    public function updateGuest
    (
        $guid,
        CustomersRequest $request,
        CustomersRepository $repository
    )
    {
        $res = $repository->updateGuest($guid, $request);
        if (!$res) return $this->sendError('设置失败');
        return $this->sendResponse($res, '设置成功');
    }

    // 转移客源
    public function transfer
    (
        $guid,
        CustomersRequest $request,
        CustomersRepository $repository
    )
    {
        $res = $repository->transfer($guid, $request);
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
        CustomersRepository $repository
    )
    {
        $res = $repository->normalCustomer();
        return $this->sendResponse($res,'正常状态客源下拉数据获取成功');
    }

}
