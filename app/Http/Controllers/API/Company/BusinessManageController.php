<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Services\BusinessManageService;
use Illuminate\Http\Request;

class BusinessManageController extends APIBaseController
{
    // 操作记录
    public function index(
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->BusinessList($request);
        return $this->sendResponse($res,'业务服务列表获取成功');
    }
}
