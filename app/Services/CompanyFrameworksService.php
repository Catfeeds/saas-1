<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;

class CompanyFrameworksService
{
    // 通过公司获取所有用户
    public function adoptCompanyGetUser(
        $request
    )
    {
        return User::where('company_guid', $request->company_guid)->get();
    }

    // 通过区域获取所有用户
    public function adoptAreaGetUser(
        $request
    )
    {
        // 获取区域下门店guid
        $storefrontGuid = CompanyFramework::where('parent_guid', $request->area_guid)->pluck('guid');
        // 获取区域下组guid
        $groupGuid = CompanyFramework::whereIn('parent_guid', $storefrontGuid)->pluck('guid');
        $guid = array_merge($storefrontGuid, $groupGuid);
        return User::whereIn('rel_guid', $guid)->get();
    }

    // 通过门店/组获取所有用户
    public function adoptConditionGetUser(
        $request
    )
    {
        return User::where('rel_guid', $request->rel_guid)->get();
    }

    // 通过用户名称获取用户
    public function adoptNameGetUser(
        $request
    )
    {
       return User::where('name','like','%,'.$request->name.',%')->get();
    }

    //获取登录人公司的全部门店下拉数据
    public function getStorefront()
    {
        $res = CompanyFramework::where(['company_guid' => Common::user()->company_guid, 'level' => 2])->get();
        return $res->map(function($v) {
            return [
                'value' => $v->guid,
                'name' => $v->name
            ];
        });
    }

    //获取登录人公司的全部分组下拉数据
    public function getGroup()
    {
        $res = CompanyFramework::where(['company_guid' => Common::user()->company_guid, 'level' => 3])->get();
        return $res->map(function($v) {
            return [
                'value' => $v->guid,
                'name' => $v->name
            ];
        });
    }


}