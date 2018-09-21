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
        $service->BusinessList($request);








    }
}
