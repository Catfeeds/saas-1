<?php

namespace App\Services;

use App\Models\CompanyFramework;
use App\Models\User;

class CompanyFrameworksService
{
    // 通过公司获取所有用户
    public function adoptCompanyGetUser(
        $request
    )
    {
        if (!empty($request->status)) {
            $status = [1, 2, 3];
        } else {
            $status = [1, 3];
        }

        return User::where('company_guid', $request->company_guid)->whereIn('status', $status)->get();
    }

    // 通过区域获取所有用户
    public function adoptAreaGetUser(
        $request
    )
    {
        // 获取区域下门店guid
        $storefrontGuid = CompanyFramework::where('parent_guid', $request->area_guid)->pluck('guid')->toArray();
        // 获取区域下组guid
        $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid')->toArray();
        $guid = array_merge($storefrontGuid, $groupGuid);

        if (!empty($request->status)) {
            $status = [1, 2, 3];
        } else {
            $status = [1, 3];
        }
        return User::whereIn('rel_guid', $guid)->whereIn('status', $status)->get();
    }

    // 通过门店/组获取所有用户
    public function adoptConditionGetUser(
        $request
    )
    {
        if (!empty($request->status)) {
            $status = [1, 2, 3];
        } else {
            $status = [1, 3];
        }

        return User::where('rel_guid', $request->rel_guid)->whereIn('status', $status)->get();
    }

    // 通过用户名称获取用户
    public function adoptNameGetUser(
        $request
    )
    {
       return User::where('name','like','%,'.$request->name.',%')->get();
    }


}