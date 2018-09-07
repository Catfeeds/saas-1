<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{
    // 片区,门店,分组 3级菜单
    public function getList()
    {
        $areas = CompanyFramework::with('framework')->where([
            'parent_guid' => null,
            'company_guid' => Common::user()->company_guid
        ])->orderBy('level','asc')
            ->orderBy('created_at','asc')
            ->get();
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
                        'label' => $group->name,
                        'level' => $group->level,
                        'parent_guid' => $group->parent_guid
                    ];
                    $group_data[] = $item;
                }
                $store_data[] = [
                    'value' => $store->guid,
                    'label' => $store->name,
                    'level' => $store->level,
                    'parent_guid' => $store->parent_guid,
                    'children' => $group_data
                ];
            }
            $data = [
                'value' => $area->guid,
                'label' => $area->name,
                'level' => $area->level,
                'parent_guid' => '',
                'children' => $store_data
            ];
            $box[] =  $data;
        }
        return $box;
    }

    // 新增片区
    public function addArea($request)
    {
        \DB::beginTransaction();
        try {
            $area =  CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 1,
                'company_guid' => Common::user()->company_guid,    // TODO 获取登录人的公司guid 已修改
            ]);
            if (empty($area)) throw new \Exception('片区添加失败');

            if ($request->storefront_guid) {
                $res = CompanyFramework::whereIn('guid', $request->storefront_guid)->update(['parent_guid' => $area->guid]);
                if (empty($res)) throw new \Exception('片区关联门店失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 新增门店
    public function addStorefront($request)
    {
        \DB::beginTransaction();
        try {
            $store = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 2,
                'company_guid' => Common::user()->company_guid,    // TODO 获取登录人的公司guid 已修改
                'parent_guid' => $request->parent_guid
            ]);
            if (empty($store)) throw new \Exception('门店添加失败');

            // 处理人员
            if ($request->userGuid) {
                $res = User::whereIn('guid', $request->userGuid)->update(['rel_guid' => $store->guid]);
                if (empty($res)) throw new \Exception('片区关联门店失败');
            }
            \DB::commit();
            return true;
        }catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 新增分组
    public function addGroup($request)
    {
        \DB::beginTransaction();
        try {
            $group = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'level' => 3,
                'company_guid' => Common::user()->company_guid,
                'parent_guid' => $request->parent_guid
            ]);
            if (empty($group)) throw new \Exception('分组添加失败');

            if ($request->userGuid) {
                $res = User::whereIn('guid', $request->userGuid)->update(['rel_guid' => $group->guid]);
                if (empty($res)) throw new \Exception('分组关联门店失败');
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 修改片区、门店、分组
    public function updateData($companyFramework, $request)
    {
        $companyFramework->name = $request->name;
        $companyFramework->parent_guid = $request->parent_guid;
        if (!$companyFramework->save()) return false;
        return true;

    }
}