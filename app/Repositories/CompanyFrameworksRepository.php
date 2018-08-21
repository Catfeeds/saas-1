<?php

namespace App\Repositories;

use App\Models\CompanyFramework;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{

    public function getList($request)
    {
        //查询登录人公司全部人员
//        $user = User::where('company_guid', 'aaa12');
        //查出一级划分
        $areas = CompanyFramework::where([
            'company_guid' => 'aaa12',
            'parent_guid' => null,
            'level' => 1
        ])->get();



    }
}