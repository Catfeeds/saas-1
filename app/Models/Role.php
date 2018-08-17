<?php

namespace App\Models;

class Role extends BaseModel
{
    public function roleHasPermission()
    {
        return $this->hasMany('App\Models\RoleHasPermission','role_guid','guid');
    }
}
