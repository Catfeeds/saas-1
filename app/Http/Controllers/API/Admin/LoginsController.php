<?php

namespace App\Http\Controllers\API\Admin;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Admin\LoginsRequest;
use App\Services\LoginsService;
use Laravel\Passport\Token;

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

    //退出登录
    public function logout()
    {
        $user = Common::admin();
        if (empty($user)) {
            return $this->sendError('暂未登录', 403);
        }

        // 获取当前登陆用户的access_token的id
        $accessToken = $user->access_token;
        // 找到这条access_token并且将其删除
        $token = Token::find($accessToken);
        if (empty($token)) return $this->sendError('暂无有效令牌', 403);

        if (!empty($token->delete())) {
            return $this->sendResponse([], '退出成功！');
        } else {
            return $this->sendError('退出失败', 500);
        }
    }

}
