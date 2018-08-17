<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\RolesRequest;
use App\Models\Role;
use App\Repositories\RoleHasPermissionsRepository;
use App\Repositories\RoleRepository;
use Illuminate\Http\Request;

class RolesController extends APIBaseController
{

    public function index
    (
        RolesRequest $request,
        RoleRepository $repository
    )
    {
        $res = $repository->getList($request);
        return $this->sendResponse($res, '角色列表获取成功');
    }
    
    //添加角色
    public function store
    (
        RolesRequest $request,

        RoleRepository $repository
    )
    {
        $res = $repository->addRole($request);
        if (!$res) return $this->sendError('角色添加失败');
        return $this->sendResponse($res,'添加角色成功');
    }

    //修改角色名称
    public function updateRoleName
    (
        RolesRequest $request,
        RoleRepository $repository
    )
    {
        $res = $repository->updateRoleName($request);
        return $this->sendResponse($res,'修改角色名称成功');
    }

    //修改角色级别
    public function updateRoleLevel
    (
        RolesRequest $request,
        RoleRepository $repository
    )
    {
        $res = $repository->updateRoleLevel($request);
        return $this->sendResponse($res,'修改角色级别成功');
    }

    public function updatePermission
    (
        RolesRequest $request,
        RoleRepository $repository
    )
    {

    }

    //删除角色 
    public function destroy
    (
        Role $role,
        RoleRepository $repository
    )
    {
        $res = $repository->delRole($role);
        if (!$res) return $this->sendError('删除失败');
        return $this->sendResponse($res, '删除成功');
    }

    // 修改角色权限
    public function updateRolePermission(
        Request $request,
        RoleHasPermissionsRepository $repository
    )
    {
        $res = $repository->updateRolePermission($request);
        return $this->sendResponse($res,'角色权限修改成功');
    }
}
