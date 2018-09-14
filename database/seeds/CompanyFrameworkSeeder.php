<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\Role;
use App\Models\User;
use App\Services\QuartersService;
use Illuminate\Http\Request;

class CompanyFrameworkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quartersService = new QuartersService();
        $request = new Request();

        // 添加公司
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '楚楼网',
            'city_guid' => '134dba069ad211e8b2e4144fd7c018f6',
            'area_guid' => '13cc70129ad211e8b005144fd7c018f6',
            'address' => '金融港光谷智慧园',
            'company_tel' => '400-580-888'
        ]);

        // 添加管理员角色
         $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '管理员',
            'level' => 1,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 1);
        $quartersService->defaultPermissions($request);

         // 添加管理员账户
        User::create([
            'guid' => Common::getUuid(),
            'role_guid' => $role->guid,
            'tel' => '17707234721',
            'name' => '黄智鑫',
            'remarks' => 'COO',
            'password' => bcrypt('chulouwang888'),
            'company_guid' => $company->guid,
        ]);

        // 添加市场总监角色
        $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '市场总监',
            'level' => 1,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 1);
        $quartersService->defaultPermissions($request);

        // 添加区域经理角色
        $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '区域经理',
            'level' => 2,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 2);
        $quartersService->defaultPermissions($request);

        // 添加店长角色
        $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '店长',
            'level' => 3,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 3);
        $quartersService->defaultPermissions($request);

        // 添加组长角色
        $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '组长',
            'level' => 4,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 4);
        $quartersService->defaultPermissions($request);

        // 添加经纪人角色
        $role = Role::create([
            'guid' => Common::getUuid(),
            'company_guid' => $company->guid,
            'name' => '经纪人',
            'level' => 5,
        ]);
        $request->offsetSet('role_guid', $role->guid);
        $request->offsetSet('level', 5);
        $quartersService->defaultPermissions($request);

        // 添加武昌片区
        $area1 = CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '武昌',
            'company_guid' => $company->guid,
            'level' => 1
        ]);

        // 添加汉口片区
        $area2 = CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '汉口',
            'company_guid' => $company->guid,
            'level' => 1
        ]);

        // 添加门店
        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '智慧园店-楚楼总部',
            'company_guid' => $company->guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '光谷店',
            'company_guid' => $company->guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '汉街店',
            'company_guid' => $company->guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '汉口泛海国际店',
            'company_guid' => $company->guid,
            'parent_guid' => $area2->guid,
            'level' => 2
        ]);
    }
}
