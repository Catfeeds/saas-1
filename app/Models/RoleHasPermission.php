<?php

namespace App\Models;

class RoleHasPermission extends BaseModel
{
    protected $appends = [
        'action_scope_cn',
        'follow_up_cn'
    ];

    public function hasPermission()
    {
        return $this->hasOne(Permission::class,'guid', 'permission_guid');
    }

    //作用域中文
    public function getActionScopeCnAttribute()
    {
        switch ($this->action_scope) {
            case 1:
                return '全公司';
                break;
            case 2:
                return '本区';
                break;
            case 3:
                return '本店';
                break;
            case 4:
                return '本组';
                break;
            case 5:
                return '本人' ;
                break;
            case 6:
                return '无';
                break;
                default;
                break;
        }
    }

    //是否跟进中文
    public function getFollowUpCnAttribute()
    {
        switch ($this->follow_up) {
            case 1:
                return '查看前';
                break;
            case 2:
                return '查看后';
                break;
            case 3:
                return '不需要';
                break;
                default;
                break;
        }
    }
}
