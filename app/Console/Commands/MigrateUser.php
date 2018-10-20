<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Company;
use App\Models\CompanyFramework;
use App\Models\MediaUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新人员';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // 先更新黄建公司
        $company_guid = Company::where('name', '武昌关山区域')->value('guid');

        // 更新黄建公司人员
        $guid = CompanyFramework::whereIn('name', ['汉街店', '光谷总部时代'])->pluck('guid')->toArray();

        $user = User::whereIn('rel_guid', $guid)->get();
        foreach ($user as $v) {
            // 查询该用户角色等级
            $level = $v->role->level;
            $role_guid = Role::where(['company_guid' => $company_guid, 'level' => $level])->value('guid');
            // 去新公司查询role_guid
            $v->update(['company_guid' => $company_guid, 'role_guid' => $role_guid]);
        }

        // 更新程达公司
        $company_guid = Company::where('name', '关山区域')->value('guid');
        // 更新黄建公司人员
        $guid = CompanyFramework::where('name', '智慧园店-楚楼总部')->value('guid');
        $user = User::where('rel_guid', $guid)->get();
        foreach ($user as $v) {
            // 查询该用户角色等级
            $level = $v->role->level;
            $role_guid = Role::where(['company_guid' => $company_guid, 'level' => $level])->value('guid');
            // 去新公司查询role_guid
            $v->update(['company_guid' => $company_guid, 'role_guid' => $role_guid]);
        }
    }
}
