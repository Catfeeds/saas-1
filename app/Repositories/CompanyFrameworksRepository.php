<?php

namespace App\Repositories;

use App\Models\CompanyFramework;
use App\Models\User;
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
    public function newArea($arr)
    {
        \DB::beginTransaction();
        
    }
}