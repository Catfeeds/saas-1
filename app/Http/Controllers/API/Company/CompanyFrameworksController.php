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
    public function newArea
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->newArea($request);
        return $this->sendResponse($res,'新增片区成功');
    }
    //新增门店
    public function newStore
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->newStore($request);
        return $this->sendResponse($res,'新增门店成功');
    }

   //新增分组
    public function newGroup
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->newGroup($request);
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
    
}
