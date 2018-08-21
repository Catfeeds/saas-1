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
    public function add
    (
        CompanyFrameworksRequest $request,
        CompanyFrameworksRepository $repository
    )
    {
        $res = $repository->addStore($request);
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


}
