<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\RolesRequest;
use App\Models\Role;
use App\Repositories\RoleRepository;

class RolesController extends APIBaseController
{
    //添加角色
    public function store
    (
        RolesRequest $request,
        RoleRepository $repository
    )
    {
        $res = $repository->addRole($request);
        return $this->sendResponse($res,'添加角色成功');
    }
    //修改之前原始数据
    public function edit(Role $role)
    {
        return $this->sendResponse($role,'获取修改前原始数据成功');
    }

    //修改角色名称
    public function update
    (
        RolesRequest $request,
        RoleRepository $repository,
        Role $role
    )
    {
        $res = $repository->updateRoleName($request,$role);
        return $this->sendResponse($res,'修改角色名称成功');
    }

    //修改角色级别
    public function show
    (
        RolesRequest $request,
        RoleRepository $repository,
        Role $role
    )
    {
        $res = $repository->updateRloeLevel($request,$role);
        return $this->sendResponse($res,'修改角色级别成功');
    }

    //删除角色 TODO 必须删除与角色相关数据
    public function destroy(Role $role)
    {
        $res = $role->delete();
        return $this->sendResponse($res,'删除角色成功');
    }
}
