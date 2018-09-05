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

    // 通过公司/区域/门店/组获取所有用户
    public function adoptConditionGetUser($request)
    {
        if ($request->area_guid) {
            // 获取区域下门店guid
            $storefrontGuid = CompanyFramework::where(['parent_guid' => $request->area_guid, 'level' => 2])->pluck('guid')
                ->toArray();
            // 获取区域下组guid
            $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();
            $guid = array_merge($storefrontGuid, $groupGuid);
            return User::whereIn('rel_guid', $guid)->with(['role', 'companyFramework'])->paginate($request->per_page ?? 10);
        } elseif ($request->storefront_guid) {
            // 获取门店下组guid
            $storefrontGuid = CompanyFramework::where(['parent_guid' => $request->storefront_guid, 'level' => 3])->pluck
            ('guid')
                ->toArray();
            // 将门店guid拼接到关联数据中
            $storefrontGuid[] = $request->storefront_guid;
            return User::whereIn('rel_guid', $storefrontGuid)->with(['role', 'companyFramework'])->paginate($request->per_page ?? 10);
        } elseif ($request->group_guid) {
            return User::where('rel_guid', $request->group_guid)->with(['role', 'companyFramework'])->paginate($request->per_page ?? 10);
        } else {
            return User::where('company_guid', Common::user()->company_guid)->with(['role', 'companyFramework'])->paginate($request->per_page ?? 10);
        }
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