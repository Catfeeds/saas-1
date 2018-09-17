<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\SharesRequest;
use App\Services\HousesService;
use App\Services\SharesService;

class SharesController extends APIBaseController
{

    // 共享房源
    public function index
    (
        SharesRequest $request,
        SharesService $service,
        HousesService $housesService
    )
    {
        $res = $service->getList($housesService, $request);
        return $this->sendResponse($res, '共享房源列表获取成功');
    }

    // 共享房源详情
    public function show
    (
        $guid,
        SharesService $service
    )
    {
        $res = $service->getInfo($guid);
        return $this->sendResponse($res, '共享房源详情获取成功');
    }

    // 加盟商共享房源列表
    public function companyShares
    (
        SharesRequest $request,
        SharesService $service,
        HousesService $housesService
    )
    {
        $res = $service->getCompanyList($housesService, $request);
        return $this->sendResponse($res, '公司共享房源列表获取成功');
    }

    // 加盟商共享房源详情
    public function companyShow
    (
        SharesRequest $request,
        SharesService $service
    )
    {
        $res = $service->getCompanyInfo($request->guid);
        return $this->sendResponse($res, '公司共享房源详情获取成功');
    }
}
