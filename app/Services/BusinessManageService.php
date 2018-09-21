<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\User;

class BusinessManageService
{
    public function BusinessList($request)
    {
        // 获取当前查看所有角色
        $usersGuid = Access::getUser(Common::user()->role->level);

        // 获取所有角色相关信息
        $users = User::whereIn('guid', $usersGuid)
            ->withCount([
                'house',    // 房源
                'customer', // 客源
                'houseTrack',  // 房源跟进
                'customerTrack',   // 客源跟进
                'houseVisit',  // 房源带看
                'customerVisit',   // 客源带看
                'seeHouseWay',  // 提交钥匙
                'recordImg',    // 上传图片
                'recordHouseNumber',    // 房号
                'recordOwnerInfo'   // 业主信息
            ])->paginate($request->per_page ?? 10);

        return $users;
    }
    
    
}