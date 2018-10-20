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
        // 先更新鄢志敏公司
        $company_guid = Company::where('name', '汉口区域')->value('guid');
        dd($company_guid);

        // 更新黄建公司人员
        $guid = CompanyFramework::where('name', '汉口泛海国际店')->pluck('guid')->toArray();

        $user = User::whereIn('rel_guid', $guid)->get();
        foreach ($user as $v) {
            // 查询该用户角色等级
            $level = $v->role->level;
            $role_guid = Role::where(['company_guid' => $company_guid, 'level' => $level])->value('guid');
            // 去新公司查询role_guid
            $v->update(['company_guid' => $company_guid, 'role_guid' => $role_guid]);
        }

        // 更新程达公司
        $company_guid = Company::where('name', '光谷区域')->value('guid');
        // 更新黄建公司人员
        $guid = CompanyFramework::where('name', '光谷店')->value('guid');
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
