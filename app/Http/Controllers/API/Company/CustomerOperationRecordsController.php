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
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse($res['message'], '动态获取成功');
    }
}
