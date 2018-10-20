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

        //----汉口区域--------
        $area_guid = Area::where('name', '江岸区')->value('guid');

        // 新建公司(武昌关山区域)
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '汉口区域',
            'city_guid' => $city_guid,
            'area_guid' => $area_guid,
            'address' => '汉口',
            'company_tel' => '18772664414',
            'contacts' => '鄢志敏',
            'contacts_tel' => '18772664414',
            'job_remarks' => '经理',
            'status' => 1
        ]);

        // 修改 所属公司
        CompanyFramework::where('name', '汉口泛海国际店')->update(['company_guid' => $company->guid, 'parent_guid' => null]);

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
        $user = User::where(['tel' => '18772664414', 'name' => '鄢志敏'])->first();
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

        //---- 光谷区域 --------
        $area_guid = Area::where('name', '东湖高新区')->value('guid');

        // 新建公司(武昌关山区域)
        $company = Company::create([
            'guid' => Common::getUuid(),
            'name' => '光谷区域',
            'city_guid' => $city_guid,
            'area_guid' => $area_guid,
            'address' => '光谷',
            'company_tel' => '15527661257',
            'contacts' => '王玺',
            'contacts_tel' => '15527661257',
            'job_remarks' => '经理',
            'status' => 1
        ]);
        // 修改总部时代店/汉街店 所属公司
        CompanyFramework::where(['name' => '光谷店', 'level' => 2])->update(['company_guid' => $company->guid, 'parent_guid' => null]);
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
        $user = User::where(['tel' => '15527661257', 'name' => '王玺'])->first();
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
