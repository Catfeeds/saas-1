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
    protected $description = '迁移人员';

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
        $company_guid = Company::where('name', '楚楼网')->value('guid');
            // 迁移人员
            $user = MediaUser::with('storefront')->where('ascription_store', '!=', 6)->get();
            foreach ($user as $v) {
                // 查询对应门店的guid
                if ($v->storefront) {
                    $guid = CompanyFramework::where('name', $v->storefront->storefront_name)->value('guid');
                } else {
                    $guid = null;
                }
                // 员工角色
                $role = Role::where('company_guid', $company_guid);
                if ($v->level == 1) {
                    $role_guid = $role->where('name', '!=', '管理员')->where('level', 1)->value('guid');
                } elseif ($v->level == 6) {
                    $role_guid = $role->where('level', 5)->value('guid');
                } else {
                    $role_guid = $role->where('level', $v->level)->value('guid');
                }
                // 插入新数据
                $user = User::create([
                    'guid' => Common::getUuid(),
                    'openid' => $v->openid,
                    'company_guid' => $company_guid,
                    'rel_guid' => $guid ,// 员工关联架构guid
                    'name' => $v->real_name,
                    'tel' => $v->tel,
                    'password' => $v->password,
                    'role_guid' => $role_guid, //根据等级关联对应guid
                    'status' => $v->remark ? 2 : 1,
                ]);
                if (!$user) \Log::info($user->id.'添加失败');
            }
    }
}