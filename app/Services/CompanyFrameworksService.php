<?php

namespace App\Services;

use App\Models\CompanyFramework;
use App\Models\User;

class CompanyFrameworksService
{
    // 通过公司/区域/门店/组获取所有用户
    public function adoptConditionGetUser(
        $request
    )
    {
        if ($request->area_guid) {
            // 获取区域下门店guid
            $storefrontGuid = CompanyFramework::where('parent_guid', $request->area_guid)->pluck('guid')->toArray();
            // 获取区域下组guid
            $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();
            $guid = array_merge($storefrontGuid, $groupGuid);
            return User::whereIn('rel_guid', $guid)->paginate(10);
        } elseif($request->storefront_guid) {
            // 获取门店下组guid
            $storefrontGuid = CompanyFramework::where('parent_guid', $request->storefront_guid)->pluck('guid')->toArray();
            // 将门店guid拼接到关联数据中
            $storefrontGuid[] = $request->storefront_guid;
            return User::whereIn('rel_guid', $storefrontGuid)->paginate(10);
        } elseif($request->group_guid) {
            return User::where('rel_guid', $request->group_guid)->paginate(10);
        } else {
            return User::where('company_guid', $request->company_guid)->paginate(10);
        }
    }

    // 通过用户名称获取用户
    public function adoptNameGetUser(
        $request
    )
    {
        return User::where('name', 'like' , '%,'.$request->name.',%')->get();
    }

    // 根据条件获取所有区域/门店/组
    public function getAllBasicsInfo(
        $request
    )
    {
        // TODO 公司guid

        if (!in_array($request->level, [1, 2, 3]) && empty($request->level)) return collect();
        return CompanyFramework::where(['company_guid' => 'asdasdas', 'level' => $request->level])->get();
    }

    //通过门店获取分组
    public function getGroup($storefrontId)
    {
        $storefront = CompanyFramework::where('parent_guid', $storefrontId)->get();
        return $storefront->map(function($v) {
           return [
               'value' => $v->guid,
               'name' => $v->name
           ];
        });
    }
}