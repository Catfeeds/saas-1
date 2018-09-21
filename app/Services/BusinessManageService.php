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
            ->with([
                'house.track',  // 房源跟进
                'customer.track',   // 客源跟进
                'customer.visit',   // 客源带看
                'house.visit',  // 房源带看
                  
            ])->get();

        dd($users[0]);



    }
    
    
}