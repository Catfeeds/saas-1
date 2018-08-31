<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\HouseOperationRecordsRequest;
use App\Services\HousesService;

class HouseOperationRecordsController extends APIBaseController
{
    // 操作记录
    public function index
    (
        HouseOperationRecordsRequest $request,
        HousesService $service
    )
    {
        $res = $service->getDynamic($request);
        return $this->sendResponse($res, '动态获取成功');
    }
}
