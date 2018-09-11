<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\SharesRequest;
use App\Models\House;
use App\Services\HousesService;
use App\Services\SharesService;

class SharesController extends APIBaseController
{
    // 加盟商共享房源列表
    public function index
    (
        SharesRequest $request,
        SharesService $service
    )
    {
        $res = $service->getList($request);
        return $this->sendResponse($res, '共享房源列表获取成功');
    }

    // 加盟商共享房源详情
    public function show
    (
        $guid,
        SharesService $service
    )
    {
        $res = $service->getInfo($guid);
        return $this->sendResponse($res, '共享房源详情获取成功');
    }

}
