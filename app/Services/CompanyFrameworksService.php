<?php

namespace App\Services;

use App\Handler\Common;
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
            return User::whereIn('rel_guid', $guid)->with(['role', 'companyFramework'])->paginate($request->per_page??10);
        } elseif($request->storefront_guid) {
            // 获取门店下组guid
            $storefrontGuid = CompanyFramework::where('parent_guid', $request->storefront_guid)->pluck('guid')->toArray();
            // 将门店guid拼接到关联数据中
            $storefrontGuid[] = $request->storefront_guid;
            return User::whereIn('rel_guid', $storefrontGuid)->with(['role', 'companyFramework'])->paginate($request->per_page??10);
        } elseif($request->group_guid) {
            return User::where('rel_guid', $request->group_guid)->with(['role', 'companyFramework'])->paginate($request->per_page??10);
        } else {
            return User::where('company_guid', Common::user()->company_guid)->with(['role', 'companyFramework'])->paginate($request->per_page??10);
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
        if (!in_array($request->level, [1, 2, 3]) && empty($request->level)) return collect();
        return CompanyFramework::where(['company_guid' => Common::user()->company_guid, 'level' => $request->level])->get();
    }

    //通过门店获取分组
    public function  getGroup($storefrontId)
    {
        $storefront = CompanyFramework::where('parent_guid', $storefrontId)->get();
        return $storefront->map(function($v) {
           return [
               'value' => $v->guid,
               'name' => $v->name
           ];
        });
    }

    //删除
    public function deleteData($data)
    {
        \DB::beginTransaction();
        try {
            //查询所有分组,循环删除
            if (!empty($data[2])) {
                $group = CompanyFramework::with('users')->whereIn('guid', $data[2])->get();
                foreach ($group as $v) {
                    if (!$v->users->isEMpty()) return ['status' => false, 'message' => '删除失败,'. $v->name. '下还有员工'];
                    $v->delete();
                }
            }

           //查询所有门店
            if (!empty($data[1])) {
                $storefront = CompanyFramework::with('framework')->whereIn('guid', $data[1])->get();
                foreach ($storefront as $v) {
                    if (!$v->framework->isEMpty()) return ['status' => false, 'message' => '删除失败,'. $v->name. '下还有分组'];
                    $v->delete();
                }
            }

            //删除片区
            if (!empty($data[0])) {
                $area = CompanyFramework::with('framework')->whereIn('guid', $data[0])->get();
                foreach ($area as $v) {
                    if (!$v->framework->isEMpty()) return ['status' => false, 'message' => '删除失败,'. $v->name. '下还有门店'];
                    $v->delete();
                }
            }
            \DB::commit();
            return ['status' => true, 'message' => '删除成功'];
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('删除失败'.$exception->getMessage());
            return ['status' => false, 'message' => '删除失败'];
        }
    }
}