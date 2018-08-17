<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Database\Eloquent\Model;

class RoleHasPermissionsRepository extends Model
{
    // 修改角色权限
    public function updateRolePermission(
        $request
    )
    {
        return RoleHasPermission::where([
            'role_guid' => $request->role_guid,
            'permission_guid' => $request->permission_guid
        ])->update([
            'action_scope' => $request->action_scope,
            'operation_number' => $request->operation_number,
            'follow_up' => $request->follow_up
        ]);
    }

    // 岗位设置列表
    public function roleHasPermissionList()
    {
        $res = Role::where('company_guid','asdads455645')->with('roleHasPermission')->get();

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
    
    
}