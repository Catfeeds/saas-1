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

    public static function getUser(
        $actionScope
    )
    {
        if ($actionScope == 1) {
            // 全公司
        } elseif ($actionScope == 2) {
            // 区域
        } elseif ($actionScope == 3) {
            // 门店
        } elseif ($actionScope == 4) {
            // 组
        } elseif ($actionScope == 5) {
            // 个人
        }
    }
}