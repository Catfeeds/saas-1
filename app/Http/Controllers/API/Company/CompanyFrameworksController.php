<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CompanyFrameworksRequest;
use App\Repositories\CompanyFrameworksRepository;

class CompanyFrameworksController extends APIBaseController
{

    public function index
    (
        CompanyFrameworksRepository $repository,
        CompanyFrameworksRequest $request
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res, '列表获取成功');
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


}
