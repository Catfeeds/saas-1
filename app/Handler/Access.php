<?php

namespace App\Handler;

use App\Models\Company;
use App\Models\CompanyFramework;
use App\Models\Permission;
use App\Models\RoleHasPermission;
use App\Models\User;

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

    // 获取公司/区域/门店/组所有用户
    public static function getUser(
        $actionScope
    )
    {
        if ($actionScope == 1) {
            // 全公司
            return User::where([
                'company_guid' => Common::user()->company_guid,
                'status' => 1
            ])->pluck('guid')->toArray();
        } elseif ($actionScope == 2) {
            // 区域所有guid
            $areaGuid = CompanyFramework::where([
                'company_guid' => Common::user()->company_guid,
                'level' => 1
            ])->pluck('guid')->toArray();

            // 门店所有guid
            $storefrontGuid = CompanyFramework::whereIn('parent_guid' ,$areaGuid)->pluck('guid')->toArray();
            // 组所有guid
            $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();

            $guids = array_merge($storefrontGuid, $groupGuid);

            return User::whereIn('rel_guid', $guids)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 3) {
            // 门店
            $storefrontGuid = CompanyFramework::where([
                'company_guid' => Common::user()->company_guid,
                'level' => 2
            ])->pluck('guid')->toArray();

            // 组所有guid
            $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();

            $guids = array_merge($storefrontGuid, $groupGuid);

            return User::whereIn('rel_guid', $guids)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 4) {
            // 组
            $groupGuid = CompanyFramework::where([
                'company_guid' => Common::user()->company_guid,
                'level' => 3
            ])->pluck('guid')->toArray();

            return User::whereIn('rel_guid', $groupGuid)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 5) {
            // 个人
            return Common::user()->guid;
        }
    }
}