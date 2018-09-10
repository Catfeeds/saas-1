<?php

namespace App\Handler;

use App\Models\CompanyFramework;
use App\Models\House;
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
        // 获取登录人角色guid
        $user = Common::user();
        $role_guid = $user->role->guid;
        $permission_guid = Permission::where('name_en', $name)->value('guid');
        if (!empty($permission_guid)) return RoleHasPermission::where(['permission_guid' => $permission_guid, 'role_guid' => $role_guid])->first();
    }

    // 获取登录人的所有权限
    public static function access($user = null)
    {
        // 获取当前登录人
        if (empty($user)) $user = Common::user();
        $res = $user::with('role','role.roleHasPermission', 'role.roleHasPermission.hasPermission')->first();
        $permission = [];
        foreach ($res->role->roleHasPermission as $k => $v) {
            $permission[$k]['permission_guid'] = $v->permission_guid;
            $permission[$k]['permission_name'] = $v->hasPermission->name;
            $permission[$k]['action_scope'] = $v->action_scope;
            $permission[$k]['action_scope_cn'] = $v->action_scope_cn;
            $permission[$k]['follow_up'] = $v->follow_up;
            $permission[$k]['follow_up_cn'] = $v->follow_up_cn;
            $permission[$k]['operation_number'] = $v->operation_number;
        }
        return $permission;
    }

    // 获取公司/区域/门店/组所有用户
    public static function getUser(
        $actionScope
    )
    {
        $guid = Common::user()->guid;

        if ($actionScope == 1) {
            // 全公司
            $res =  User::where([
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

            $res =  User::whereIn('rel_guid', $guids)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 3) {
            // 门店
            $storefrontGuid = CompanyFramework::where([
                'company_guid' => Common::user()->company_guid,
                'level' => 2
            ])->pluck('guid')->toArray();

            // 组所有guid
            $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();

            $guids = array_merge($storefrontGuid, $groupGuid);

            $res = User::whereIn('rel_guid', $guids)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 4) {
            // 组
            $groupGuid = CompanyFramework::where([
                'company_guid' => Common::user()->company_guid,
                'level' => 3
            ])->pluck('guid')->toArray();

            $res = User::whereIn('rel_guid', $groupGuid)->with(['role', 'companyFramework'])->pluck('guid')->toArray();
        } elseif ($actionScope == 5) {
            // 个人
            $res = array($guid);
        } elseif ($actionScope == 6) {
            $res = array();
        }

        $res[] = $guid;
        return array_unique($res);
    }

    // 通过权限获取区间用户
    public static function adoptPermissionGetUser($permission)
    {
        // 先判断是否有房源列表权限
        $permission = self::permission($permission);
        if (empty($permission)) return ['status' => false, 'message' => '暂无权限'];
        // 判断作用域
        $guardian_person = self::getUser($permission->action_scope);
        return ['status' => true, 'message' => $guardian_person];
    }

    // 通过维护人获取所有房源信息
    public static function adoptGuardianPersonGetHouse($permission)
    {
        $guardianPerson = self::adoptPermissionGetUser($permission);

        if (empty($guardianPerson['status'])) return [];

        return House::whereIn('guardian_person', $guardianPerson['message'])->pluck('guid')->toArray();
    }


}