<?php

namespace App\Repositories;

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
    public function newArea($arr)
    {
        \DB::beginTransaction();
        
    }
}