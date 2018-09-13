<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Company;
use App\Models\CompanyFramework;
use App\Models\MediaUser;
use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateFramework extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateFramework';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移组织架构和人员';

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
        //  添加组织架构
        \DB::beginTransaction();
        try {
            // 查询公司guid
            $company_guid = Company::where('name', '楚楼网')->value('guid');
            // 添加武昌片区
            $area1 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '武昌',
                'company_guid' => $company_guid,
                'level' => 1
            ]);

            // 添加汉口片区
            $area2 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '汉口',
                'company_guid' => $company_guid,
                'level' => 1
            ]);
            if (!$area2 || !$area1) \Log::info('片区添加失败');
            // 添加门店
            $store1 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '智慧园店-楚楼总部',
                'company_guid' => $company_guid,
                'parent_guid' => $area1->guid,
                'level' => 2
            ]);

            $store2 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '光谷店',
                'company_guid' => $company_guid,
                'parent_guid' => $area1->guid,
                'level' => 2
            ]);

            $store3 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '汉街店',
                'company_guid' => $company_guid,
                'parent_guid' => $area1->guid,
                'level' => 2
            ]);

            $store4 = CompanyFramework::create([
                'guid' => Common::getUuid(),
                'name' => '汉口泛海国际店',
                'company_guid' => $company_guid,
                'parent_guid' => $area2->guid,
                'level' => 2
            ]);


            if (!$store1 || !$store2 || !$store3 || !$store4) \Log::info('门店添加失败');
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
                    $role_guid = $role->where('name', '总经理')->value('guid');
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
                    'status' => $v->remark == '已离职'? 2:1
                ]);
                if (!$user) \Log::info($user->id.'添加失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }


    }
}
