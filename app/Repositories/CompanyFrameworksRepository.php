<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{

    protected $user;

    public function __construct()
    {
        $this->user = Common::user();
    }

    public function getList($request)
    {
        //查询登录人公司全部人员
        $user = User::where('company_guid', $this->user->guid);
        //查出一级划分
        $area = CompanyFramework::where([
            'company_guid' => $this->user->company_guid,
            'parent_guid' => '',
            'level' => 1
        ])->get();
        dd($area);
    }
}