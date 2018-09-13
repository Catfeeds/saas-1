<?php
namespace App\Http\Controllers\API\Admin;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use Illuminate\Support\Facades\Auth;


class AdminsController extends APIBaseController
{
    // 登录人信息
    public function show()
    {
        $user = Auth::guard('admin')->user();
        if (empty($user)) return $this->sendError('登录账号异常');
        $res = $user->toArray();
        return $this->sendResponse($res,'用户信息获取成功');
    }
}