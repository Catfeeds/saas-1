<?php

namespace App\Models;

class Role extends BaseModel
{
    protected $appends = ['level_cn'];

    // 角色关联权限操作
    public function roleHasPermission()
    {
        return $this->hasMany('App\Models\RoleHasPermission','role_guid','guid');
    }

    public function getLevelCnAttribute()
    {
        switch ($this->level) {
            case 1:
                return '公司';
                break;
            case 2:
                return '片区';
                break;
            case 3:
                return '门店';
                break;
            case 4:
                return '分组';
                break;
            case 5:
                return '个人';
                break;
                default;
                break;
        }
    }
    
}
