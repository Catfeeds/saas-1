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
        foreach ($areas as $area) {
           //查出片区下面的门店
            $store_data = [];
            foreach ($area->framework as $store) {
                //查询门店下面的分组
                $group_data = [];
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
        $box['user'] = $user;
        return $box;
    }
}