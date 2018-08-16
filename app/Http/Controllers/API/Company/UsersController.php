<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\UsersRequest;
use App\Models\User;
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
        if ($res) {
            return $this->sendResponse($res,'添加用户成功');
        }else {
            return $this->sendError($res,'添加用户失败');
        }

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
}
