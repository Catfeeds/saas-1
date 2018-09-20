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

    // 岗位设置列表
    public function roleHasPermissionList()
    {
        $res = Role::where('company_guid', $this->user->company_guid)
            ->with('roleHasPermission','roleHasPermission.hasPermission')
            ->orderBy('level')
            ->orderBy('created_at', 'asc')
            ->get();
        $datas = array();
        foreach ($res as $k => $v) {
            $datas[$v->guid]['name'] = $v->name;
            $datas[$v->guid]['level'] = $v->level_cn;
            $permission = array();
            foreach ($v->roleHasPermission as $key => $val) {
                $permission[$val->hasPermission->name_en]['guid'] = $val->guid;
                $permission[$val->hasPermission->name_en]['action_scope'] = $val->action_scope_cn;
                $permission[$val->hasPermission->name_en]['operation_number'] = $val->operation_number;
                $permission[$val->hasPermission->name_en]['follow_up'] = $val->follow_up_cn;
                $permission[$val->hasPermission->name_en]['status'] = $val->status;
            }
            $datas[$v->guid]['permission'] = $permission;
        }
        return $datas;
    }

    // 添加角色
    public function addRole($request)
    {
        \DB::beginTransaction();
        try {
            $role = Role::create([
                'guid' => Common::getUuid(),
                'company_guid' =>  $this->user->company_guid,
                'name' => $request->name,
                'level' => $request->level
            ]);
            if (empty($role)) throw new \Exception('添加角色失败');

            // 设置参数
            $request->offsetSet('role_guid', $role->guid);

            $res = $this->defaultPermissions($request);
            if (empty($res)) throw new \Exception('岗位级别修改失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('角色添加失败'.$exception->getMessage());
            return false;
        }
    }

    // 修改角色名称
    public function updateRoleName($request)
    {
        return Role::where('guid',$request->guid)->update(['name' => $request->name]);
    }

    // 修改角色级别
    public function updateRoleLevel($request)
    {
        \DB::beginTransaction();
        try {
            $role = Role::where('guid',$request->guid)->update(['level' => $request->level]);
            if (empty($role)) throw new \Exception('岗位级别修改失败');

            $request->offsetSet('role_guid', $request->guid);
            $defaultPermissions = $this->defaultPermissions($request);
            if (empty($defaultPermissions)) throw new \Exception('默认角色设置失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 修改角色权限
    public function updateRolePermission($request)
    {
        return RoleHasPermission::where('guid', $request->guid)->update([
            'action_scope' => $request->action_scope,
            'operation_number' => $request->operation_number,
            'follow_up' => $request->follow_up,
            'status' => $request->status
        ]);
    }

    // 删除角色及相关权限关联
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

    // 默认权限
    public function defaultPermissions(
        $request
    )
    {
        $permissions = array();
        if ($request->level == 1) {
            $permissions = config('default_permission.company');
        } elseif ($request->level == 2) {
            $permissions = config('default_permission.area');
        } elseif ($request->level == 3) {
            $permissions = config('default_permission.store');
        } elseif ($request->level == 4) {
            $permissions = config('default_permission.grouping');
        } elseif ($request->level == 5) {
            $permissions = config('default_permission.personal');
        }

        foreach ($permissions as $v) {
            $permissionGuid = Permission::where('name_en', $v['name_en'])->first()->guid;

            $res = RoleHasPermission::where([
                'role_guid' => $request->role_guid,
                'permission_guid' => $permissionGuid
            ])->first();

            if (empty($res)) {
                $roleHasPermission = RoleHasPermission::create([
                    'guid' => Common::getUuid(),
                    'role_guid' => $request->role_guid,
                    'permission_guid' => $permissionGuid,
                    'action_scope' => $v['action_scope'],
                    'operation_number' => $v['operation_number'],
                    'follow_up' => $v['follow_up'],
                    'status' => 1
                ]);
                if (empty($roleHasPermission)) return false;
            } else {
                $res->action_scope = $v['action_scope'];
                $res->operation_number = $v['operation_number'];
                $res->follow_up = $v['follow_up'];
                $res->status = 1;
                if (empty($res->save())) return false;
            }
        }

        return true;
    }

}