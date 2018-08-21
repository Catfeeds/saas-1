<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompanyFrameworksRequest;
use App\Repositories\CompanyFrameworksRepository;
use App\Services\CompanyFrameworksService;
use Illuminate\Http\Request;

class CompanyFrameworksController extends APIBaseController
{

    public function index
    (
        CompanyFrameworksRepository $repository,
        CompanyFrameworksRequest $request
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res, '获取成功');
    }

   //新增片区
    public function addArea
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addArea($request);
        return $this->sendResponse($res,'新增片区成功');
    }
    //新增门店
    public function addStorefront
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addStorefront($request);
        return $this->sendResponse($res,'新增门店成功');
    }

   //新增分组
    public function addGroup
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addGroup($request);
        return $this->sendResponse($res,'新增分组成功');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
}
