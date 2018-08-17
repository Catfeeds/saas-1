<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\QuartersRequest;
use App\Services\QuartersService;

class QuartersController extends APIBaseController
{
    // 岗位设置列表
    public function index(QuartersService $service)
    {
        $res = $service->roleHasPermissionList();
        return $this->sendResponse($res,'获取岗位设置列表成功');
    }

    //添加角色
    public function store
    (
        QuartersRequest $request,
        QuartersService $service
    )
    {
        $res = $service->addRole($request);
        if (!$res) return $this->sendError('角色添加失败');
        return $this->sendResponse($res,'添加角色成功');
    }

    //修改角色名称
    public function updateRoleName
    (
        QuartersRequest $request,
        QuartersService $service
    )
    {
        $res = $service->updateRoleName($request);
        return $this->sendResponse($res,'修改角色名称成功');
    }

    //修改角色级别
    public function updateRoleLevel
    (
        QuartersRequest $request,
        QuartersService $service
    )
    {
        $res = $service->updateRoleLevel($request);
        return $this->sendResponse($res,'修改角色级别成功');
    }

    // 修改角色权限
    public function updateRolePermission(
        QuartersRequest $request,
        QuartersService $service
    )
    {
        $res = $service->updateRolePermission($request);
        return $this->sendResponse($res,'角色权限修改成功');
    }

    //删除角色
    public function destroy
    (
        $guid,
        QuartersService $service
    )
    {
        $res = $service->delRole($guid);
        if (!$res) return $this->sendError('删除失败');
        return $this->sendResponse($res, '删除成功');
    }


}
