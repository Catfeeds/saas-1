<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\UsersRequest;
use App\Models\User;
use App\Services\LoginsService;
use App\Services\UserService;

class UsersController extends APIBaseController
{
    // 添加用户
    public function store
    (
        UsersRequest $request,
        UserService $service
    )
    {


        $res = $service->addUser($request);
        if ($res) return $this->sendResponse($res,'添加用户成功');
        return $this->sendError($res,'添加用户失败');
    }

    // 修改用户
    public function update
    (
        UsersRequest $request,
        User $user,
        UserService $service
    )
    {
        $res = $service->updateUser($request,$user);
        if (empty($res)) return $this->sendError($res,'修改用户失败');
        return $this->sendResponse($res,'修改用户修改成功');
    }

    // 删除用户 TODO 必须移除所有相关数据维护人,不可恢复
    public function destroy
    (
        User $user,
        UserService $service
    )
    {
        $res = $service->del($user);
        return $this->sendResponse($res,'删除用户成功');
    }

    // 冻结用户 TODO 所有相关信息保留,可以恢复
    public function freeze
    (
        $guid,
        UserService $service
    )
    {
        $res = $service->freeze($guid);
        return $this->sendResponse($res,'冻结成功');
    }

    // 人员离职 TODO 必须移除所有相关数据维护人
    public function resignation
    (
        $guid,
        UserService $service
    )
    {
        $res = $service->resignation($guid);
        return $this->sendResponse($res, '离职成功');
    }

    // 微信确认
    public function confirmWechat
    (
        UsersRequest $request,
        LoginsService $service
    )
    {
        $tel = $service->getTel($request->saftySign);
        // 查库
        $openid = User::where('tel', $tel)->value('openid');
        // 比较openid
        if ($openid === $request->openid) {
            // 验证成功,则返回换绑二维码
            $key = $service->cipher($request->getClientIp(), $tel);
            $res = curl(config('setting.wechat_url').'/temporary/'. $key .'/update_wechat','get');
            if (empty($res->data)) return $this->sendError('二维码获取失败');
            return $this->sendResponse($res->data, '二维码获取成功');
        } else {
            return $this->sendError('验证失败');
        }
    }

    // 微信换绑
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

    // 获取全部岗位
    public function getAllQuarters(UserService $service)
    {
        $res = $service->getAllQuarters();
        return $this->sendResponse($res, '岗位获取成功');
    }

    //登录人信息
    public function show()
    {
        $user = Common::user();
        if (empty($user)) return $this->sendError('登录账户异常');
        $res = $user->toArray();
        //根据当前登录用户角色,获取所有权限
        $res['permission'] = $user->role->permission->pluck('name')->toArray()??[];
        return $this->sendResponse($res, '成功');
    }
}