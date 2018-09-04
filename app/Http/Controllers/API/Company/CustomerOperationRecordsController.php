<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\CustomerOperationRecordsRequest;
use App\Http\Requests\Company\HouseOperationRecordsRequest;
use App\Services\CustomersService;
use App\Services\HousesService;

class CustomerOperationRecordsController extends APIBaseController
{
    // 操作记录
    public function index
    (
        CustomerOperationRecordsRequest $request,
        CustomersService $service
    )
    {
        $res = $service->getDynamic($request);
        return $this->sendResponse($res, '动态获取成功');
    }
}
