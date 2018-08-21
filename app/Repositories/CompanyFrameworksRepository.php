<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{

    public function getList($request)
    {
        //查询登录人公司全部人员
        $user = User::where('company_guid', 'aaa12')->get();
        $areas = CompanyFramework::with('framework')->where('parent_guid', null)->get();
        $box = [];
        $box[] = $user;
        //循环一级划分,查询下级
        foreach ($areas as $area) {
            $store_data = [];
            //查出片区下面的门店
            foreach ($area->framework as $store) {
                $group_data = [];
                //查询门店下面的分组
                foreach ($store->framework as $group) {
                    $item = [
                        'value' => $group->guid,
                        'name' => $group->name
                    ];
                    $group_data[] = $item;
                }
                $store_data[] = [
                    'value' => $store->guid,
                    'name' => $store->name,
                    'group' => $group_data
                ];
            }
            $data = [
                'value' => $area->guid,
                'name' => $area->name,
                'store' => $store_data
            ];
            $box[] =  $data;
        }
        return $box;
    }

    //新增片区
    public function addArea($request)
    {
        \DB::beginTransaction();
        try {
           $area =  CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 1,
           ]);
        if (empty($area)) throw new \Exception('片区添加失败');

            $res = CompanyFramework::whereIn('guid',$request->arr)->update(['parent_guid' => $area->guid]);

            if (empty($area) && empty($res)) return true;
        \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增门店
    public function addStorefront($request)
    {
        \DB::beginTransation();
        try {
            $store = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 2,
                'parent_guid' => $request->parent_guid
            ]);

            // 处理人员
            $res = User::whereIn('guid', $request->userGuid)->update(['rel_guid' => $store->guid]);

            if (empty($store) && empty($res)) return true;
            \DB::commit();
        }catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增分组
    public function addGroup($request)
    {
        \DB::beginTransation();
        try {
            $group = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 3,
                'parent_guid' => $request->parent_guid
            ]);
            $res = User::whereIn('guid',$request->userGuid)->update(['rel_guid' => $group->guid]);

            if (empty($group) && empty($res)) return true;
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }
}