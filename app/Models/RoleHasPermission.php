<?php

namespace App\Models;

class RoleHasPermission extends BaseModel
{
    public function hasPermission()
    {
        return $this->hasOne(Permission::class,'guid', 'permission_guid');
    }
}
