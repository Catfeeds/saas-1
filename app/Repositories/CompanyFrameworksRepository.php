<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\CompanyFramework;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{
    //片区,门店,分组 3级菜单
    public function getList()
    {
        $areas = CompanyFramework::with('framework')->where('parent_guid', null)->get();
        $box = [];
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
    public function newArea($request)
    {
        \DB::beginTransaction();
        //查询门店的guid在公司组织架构表中的数据
        try {
           $area =  CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => $request->level,
           ]);
        if (empty($area)) throw new \Exception('片区添加失败');

        $add_area = CompanyFramework::whereIn('guid',$request->arr)->update(['parent_guid' => $area->guid]);

        if (!empty($add_area)) {
            foreach ($add_area as $v) {
                $res = CompanyFramework::create(['parent_guid' => $v->guid]);
            }
        }
        if (empty($area) && empty($res)) return true;
        \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增门店
    public function newStore($request)
    {
        \DB::beginTransation();
        try {
            $store = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => $request->level,
                'parent_guid' => $request->parent_guid
            ]);

            // 处理人员
            User::whereIn('guid', $request->userGuid)->update(['rel_guid' => $store->guid]);


            $add_store = CompanyFramework::whereIn('guid',$request->arr)->all();
            foreach ($add_store as $v) {
                if (empty($request->parent_guid)) {
                    $res = CompanyFramework::create(['parent_guid' => $v->guid]);
                } else {
                    $res = CompanyFramework::where(['parent_guid' => $request->parent_guid])->update(['parent_guid' =>
                        $v->guid]);
                }
            }
            if (empty($store) && empty($res)) return true;
            \DB::commit();
        }catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //新增分组
    public function newGroup($request)
    {
        \DB::beginTransation();
        try {
            $group = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => $request->level,
            ]);
            $add_group = CompanyFramework::whereIn('guid',$request->arr)->all();
            foreach ($add_group as $v) {
                if (empty($request->parent_guid)) {
                    $res = CompanyFramework::create(['parent_guid' => $v->guid]);
                } else {
                    $res = CompanyFramework::where(['parent_guid' => $request->parent_guid])->update(['parent_guid' =>
                        $v->guid]);
                }
            }
            if (empty($group) && empty($res)) return true;
            \DB::commit();
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    public function updateData($companyFramework, $request)
    {
        $companyFramework->name = $request->name;
        $companyFramework->parent_guid = $request->parent_guid;
        if (!$companyFramework->save()) return false;
        return true;

    }
}