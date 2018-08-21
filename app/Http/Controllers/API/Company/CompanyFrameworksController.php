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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function newArea
    (
        CompanyFrameworksRequest $request
    )
    {

    }


    //修改之前原始数据
    public function edit(CompanyFramework $companyFramework)
    {
        return $this->sendResponse($companyFramework,'修改之前原始数据');
    }

    //修改片区、门店、分组
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

    // 通过公司获取所有用户
    public function adoptCompanyGetUser(
        Request $request,
        CompanyFrameworksService $service

    )
    {
        $res = $service->adoptCompanyGetUser($request);
        return $this->sendResponse($res,'通过公司获取所有用户成功');
    }

    // 通过区域获取所有用户
    public function adoptAreaGetUser(
        Request $request,
        CompanyFrameworksService $service
    )
    {
        $res = $service->adoptAreaGetUser($request);
        return $this->sendResponse($res,'通过区域获取所有用户成功');
    }

    // 通过门店/组获取所有用户
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

    //获取登录人公司的全部门店下拉数据
    public function getStorefront(CompanyFrameworksService $service)
    {
        $res = $service->getStorefront();
        return $this->sendResponse($res, '门店下拉数据获取成功');
    }

    //获取登录人公司的全部分组下拉数据
    public function getGroup(CompanyFrameworksService $service)
    {
        $res = $service->getGroup();
        return $this->sendResponse($res, '分组下拉数据获取成功');
    }
}
