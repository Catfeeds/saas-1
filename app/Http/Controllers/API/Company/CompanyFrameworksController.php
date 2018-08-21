<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompanyFrameworksRequest;
use App\Repositories\CompanyFrameworksRepository;
use App\Services\CompanyFrameworksService;
use Illuminate\Http\Request;
use App\Models\CompanyFramework;


class CompanyFrameworksController extends APIBaseController
{
    //片区,门店,分组 3级菜单
    public function index
    (
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->getList();
        return $this->sendResponse($res, '获取成功');
    }

    // 新增片区
    public function addArea
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addArea($request);
        return $this->sendResponse($res,'新增片区成功');
    }

    // 新增门店
    public function addStorefront
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addStorefront($request);
        return $this->sendResponse($res,'新增门店成功');
    }

    // 新增分组
    public function addGroup
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addGroup($request);
        return $this->sendResponse($res,'新增分组成功');
    }


    // 修改之前原始数据
    public function edit(CompanyFramework $companyFramework)
    {
        return $this->sendResponse($companyFramework,'修改之前原始数据');
    }

    // 修改片区、门店、分组
    public function update
    (
        CompanyFramework $companyFramework,
        CompanyFrameworksRepository $repository,
        CompanyFrameworksRequest $request
    )
    {
        $res = $repository->updateData($companyFramework, $request);
        if (!$res) return $this->sendError('修改失败');
        return $this->sendResponse($res, '修改成功');
    }


    public function destroy($id)
    {
        //
    }

    // 通过公司/区域/门店/组获取所有用户
    public function adoptConditionGetUser(
        Request $request,
        CompanyFrameworksService $service
    )
    {
        $res = $service->adoptConditionGetUser($request);
        return $this->sendResponse($res,'通过门店/组获取所有用户成功');
    }

    // 通过用户名称获取用户
    public function adoptNameGetUser(
        Request $request,
        CompanyFrameworksService $service
    )
    {
        $res = $service->adoptNameGetUser($request);
        return $this->sendResponse($res,'通过用户名称获取用户成功');
    }

    // 根据条件获取所有区域/门店/组
    public function getAllBasicsInfo(
        Request $request,
        CompanyFrameworksService $service
    )
    {
        $res = $service->getAllBasicsInfo($request);
        return $this->sendResponse($res->map(function($v) {
            return [
                'value' => $v->guid,
                'label' => $v->name
            ];
        }), '获取所有门店成功');
    }

    //通过门店获取分组
    public function getGroup
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksService $service
    )
    {
        $res = $service->getGroup($request->storefrontId);
        return $this->sendResponse($res, '门店下的分组获取成功');
    }
}
