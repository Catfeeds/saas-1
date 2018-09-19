<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\SharesRequest;
use App\Services\HousesService;
use App\Services\SharesService;

class SharesController extends APIBaseController
{

    // 全部共享房源
    public function index
    (
        SharesRequest $request,
        SharesService $service,
        HousesService $housesService
    )
    {
        $request->platform = 1;
        $res = $service->getList($housesService, $request);
        return $this->sendResponse($res, '共享房源列表获取成功');
    }

    // 房源详情
    public function show
    (
        $guid,
        SharesService $service
    )
    {
        $type = 1;
        $res = $service->getInfo($guid, $type);
        return $this->sendResponse($res, '共享房源详情获取成功');
    }

    public function store
    (
        SharesRequest $request,
        HousesService $service
    )
    {
        $request->type = 1;
        $res = $service->shareHouse($request);
        if (!$res) return $this->sendError('房源共享失败');
        return $this->sendResponse($res, '房源共享成功');
    }

    public function update
    (
        SharesRequest $request,
        HousesService $service
    )
    {
        $request->type = 1;
        $res = $service->unShare($request);
        if (!$res) return $this->sendError('房源下架失败');
        return $this->sendResponse($res, '房源下架成功');
    }
}
