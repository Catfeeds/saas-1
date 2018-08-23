<?php

namespace App\Handler;

use App\Models\Permission;
use App\Models\RoleHasPermission;


/**
 * Class Access
 * 权限公共类
 * @package App\Handler
 */
class Access{

    //获取登录人某个权限具体操作
    public static function permission($name)
    {
        $permissionGuid = Permission::where('name', $name)->value('guid');
        if (!empty($permissionGuid)) return RoleHasPermission::where('permission_guid', $permissionGuid)->first();
    }
}