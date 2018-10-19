<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Models\City;
use App\Models\Area;
use App\Handler\Common;
use App\Services\QuartersService;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;
use App\Models\CompanyFramework;

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

        // 城市

        $city_guid = City::where('name', '武汉')->value('guid');
        //----总部时代店独立--------
        // 区域
        $area_guid = Area::where('name', '江夏区')->value('guid');
        // 新建公司
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '总部时代',
            'city_guid' => $city_guid,
            'area_guid' => $area_guid,
            'address' => '总部时代',
            'company_tel' => '15926289802',
            'contacts' => '总部时代老板',
            'contacts_tel' => '15926289802',
            'job_remarks' => '总经理',
            'status' => 1
        ]);

        // 修改总部时代公司
        CompanyFramework::where(['name' => '光谷总部时代', 'level' => 2])->update(['company_guid' => $company->guid]);

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
            'tel' => '123456789',
            'name' => '总部时代老板',
            'remarks' => 'BOSS',
            'password' => bcrypt('123456789'),
            'company_guid' => $company->guid,
        ]);

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

        //---- 总部时代店独立 --------
    }
}
