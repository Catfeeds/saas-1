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

        //----武昌关山区域--------
        $area_guid = Area::where('name', '武昌区')->value('guid');

        // 新建公司(武昌关山区域)
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '武昌关山区域',
            'city_guid' => $city_guid,
            'area_guid' => $area_guid,
            'address' => '关山',
            'company_tel' => '18154318180',
            'contacts' => '黄建',
            'contacts_tel' => '18154318180',
            'job_remarks' => '经理',
            'status' => 1
        ]);

        // 修改总部时代店/汉街店 所属公司
        CompanyFramework::whereIn('name', ['汉街店', '光谷总部时代'])->update(['company_guid' => $company->guid, 'parent_guid' => null]);

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

        // 更新黄建账户
        $user = User::where(['tel' => '18154318180', 'name' => '黄建'])->first();
        $user->company_guid = $company->guid;
        $user->rel_guid = null;
        $user->role_guid = $role->guid;
        $user->remarks = '经理';
        $user->save();
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

        //---- 智慧园店独立 --------

        // 新建公司(武昌关山区域)
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '关山区域',
            'city_guid' => $city_guid,
            'area_guid' => $area_guid,
            'address' => '关山',
            'company_tel' => '18571858150',
            'contacts' => '程达',
            'contacts_tel' => '18571858150',
            'job_remarks' => '经理',
            'status' => 1
        ]);
        // 修改总部时代店/汉街店 所属公司
        CompanyFramework::where(['name' => '智慧园店-楚楼总部', 'level' => 2])->update(['company_guid' => $company->guid, 'parent_guid' => null]);
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

        // 更新程达账号
        $user = User::where(['tel' => '18571858150', 'name' => '程达'])->first();
        $user->company_guid = $company->guid;
        $user->rel_guid = null;
        $user->role_guid = $role->guid;
        $user->remarks = '经理';
        $user->save();

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


    }
}
