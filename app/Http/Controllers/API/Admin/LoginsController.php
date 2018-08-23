<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Admin\LoginsRequest;
use App\Services\LoginsService;
use Illuminate\Support\Facades\Auth;

class LoginsController extends APIBaseController
{
    // 管理后台登录
    public function Logins(
        LoginsRequest $request,
        LoginsService $service
    )
    {
        $res = $service->adminLogin($request);
        if (empty($res['status'])) return $this->sendError($res['message']);
        return $this->sendResponse($res, '获取token成功！');
    }

    public function test()
    {
        dd(Auth::guard('admin')->user());
    }


}
