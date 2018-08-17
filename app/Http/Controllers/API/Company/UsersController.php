<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\API\UsersRequest;
use App\Models\User;
use App\Services\LoginsService;
use App\Services\UserService;

class UsersController extends APIBaseController
{
    //添加用户
    public function store
    (
        UsersRequest $request,
        UserService $service
    )
    {
        $res = $service->addUser($request);
        return $this->sendResponse($res,'添加用户成功');
    }

    //修改用户
    public function update
    (
        UsersRequest $request,
        User $user,
        UserService $service
    )
    {
        $res = $service->updateUser($request,$user);
        return $this->sendResponse($res,'用户修改成功');
    }

    //删除用户
    public function destroy
    (
        User $user,
        UserService $service
    )
    {
        $res = $service->del($user);
        return $this->sendResponse($res,'删除用户成功');
    }
    
    //微信确认
    public function confirmWechat
    (
        UsersRequest $request,
        LoginsService $service
    )
    {
        $tel = $service->getTel($request->saftySign);
        //查库
        $openid = User::where('tel', $tel)->value('openid');
        //比较openid
        if ($openid === $request->openid) {
            return $this->sendResponse(true, '验证成功');
        } else {
            return $this->sendError('验证失败');
        }
    }

    //微信换绑
    public function updateWechat
    (
        UsersRequest $request,
        LoginsService $service
    )
    {
        $tel = $service->getTel($request->saftySign);
        $res = User::where('tel', $tel)->update(['openid' => $request->openid]);
        if (!$res) return $this->sendError('换绑失败');
        return $this->sendResponse($res, '换绑成功');
    }
}
