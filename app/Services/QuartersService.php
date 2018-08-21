<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;

class QuartersService
{
    protected $user;

    public function __construct()
    {
        $this->user = Common::user();
    }

    //岗位设置列表
    public function roleHasPermissionList()
    {
        $res = Role::where('company_guid', $this->user->company_guid)->with('roleHasPermission')->get();
        $datas = array();
        foreach ($res as $k => $v) {
            $datas[$v->guid]['name'] = $v->name;
            $permission = array();

            foreach ($v->roleHasPermission as $key => $val) {
                $permission[$val->permission_guid] = $val;
            }
            $datas[$v->guid]['permission'] = $permission;
        }
        return $datas;
    }

    //添加角色
    public function addRole($request)
    {
        //获取全部权限
        $permissionId = Permission::all()->pluck('guid')->toArray();

        \DB::beginTransaction();
        try {
            $role = Role::create([
                'guid' => Common::getUuid(),
                'company_guid' =>  $this->user->company_guid,
                'name' => $request->name,
                'level' => $request->level
            ]);

            foreach ($permissionId as $v) {
                RoleHasPermission::create([
                    'guid' => Common::getUuid(),
                    'role_guid' => $role->guid,
                    'permission_guid' => $v,
                    'action_scope' => $role->level,
                    'operation_number' => 30,
                    'follow_up' => 1
                ]);
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('角色添加失败'.$exception->getMessage());
            return false;
        }

    }

    //修改角色名称
    public function updateRoleName($request)
    {
        return Role::where('guid',$request->guid)->update(['name' => $request->name]);
    }

    //修改角色级别
    public function updateRoleLevel($request)
    {
        return Role::where('guid',$request->guid)->update(['level' => $request->level]);
    }

    // 修改角色权限
    public function updateRolePermission($request)
    {
        return RoleHasPermission::where([
            'role_guid' => $request->role_guid,
            'permission_guid' => $request->permission_guid
        ])->update([
            'action_scope' => $request->action_scope,
            'operation_number' => $request->operation_number,
            'follow_up' => $request->follow_up,
            'status' => $request->status
        ]);
    }

    //删除角色及相关权限关联
    public function delRole($guid)
    {
        $role = Role::where('guid', $guid)->first();
        \DB::beginTransaction();
        try {
            $role->delete();
            RoleHasPermission::where('role_guid', $role->guid)->delete();
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('删除失败'.$exception->getMessage());
            return false;
        }
    }
}