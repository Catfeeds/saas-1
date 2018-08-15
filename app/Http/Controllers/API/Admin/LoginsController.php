<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Models\User;
use App\Services\LoginsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class LoginsController extends APIBaseController
{
    //微信登录查数据库
    public function index(Request $request)
    {
        return $request->openid;
        $user = User::where('openid', $request->openid)->first();
    }

    //生成唯一安全标识
    public function create
    (
        Request $request,
        LoginsService $service
    )
    {
        $code =  $request->getClientIp().'-'.time();
        $key = $service->lock($code);
        return $this->sendResponse($key, '获取成功');
    }

    //手机号,密码直接登录
    public function store
    (
        Request $request,
        LoginsService $service
    )
    {
        //查询用户是否存在
        $user = User::where(['tel' => $request->tel])->first();
        if (empty($user)) return $this->sendError('用户不存在');
        //获取token
        $token = $service->getToken($request->tel, $request->password);
        if (empty($token['success'])) return $this->sendError($token['message']);
        return $this->sendResponse($token['data'], '获取token成功！');
    }

    //退出登录
    public function logout()
    {
        $user = Auth::guard('api')->user();
        if (empty($user)) {
            return $this->sendError('暂未登录', 403);
        }

        // 获取当前登陆用户的access_token的id
        $accessToken = $user->access_token;

        // 找到这条access_token并且将其删除
        $token = Token::find($accessToken);
        if (empty($token)) {
            return $this->sendError('暂无有效令牌', 403);
        }
        if (!empty($token->delete())) {
            return $this->sendResponse([], '退出成功！');
        } else {
            return $this->sendError('退出失败', 500);
        }
    }

}
