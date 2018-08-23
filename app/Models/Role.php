<?php

namespace App\Models;

class Role extends BaseModel
{
    public function roleHasPermission()
    {
        return $this->hasMany('App\Models\RoleHasPermission','role_guid','guid');
    }

    //角色关联权限表
    public function permission()
    {
        return $this->hasManyThrough(Permission::class, RoleHasPermission::class, 'role_guid', 'guid', 'guid','permission_guid');
    }
}
