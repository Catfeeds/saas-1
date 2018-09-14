<?php

use Illuminate\Database\Seeder;
use App\Models\Company;
use App\Handler\Common;
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

        // 添加门店
        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '智慧园店-楚楼总部',
            'company_guid' => $company_guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '光谷店',
            'company_guid' => $company_guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '汉街店',
            'company_guid' => $company_guid,
            'parent_guid' => $area1->guid,
            'level' => 2
        ]);

        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '汉口泛海国际店',
            'company_guid' => $company_guid,
            'parent_guid' => $area2->guid,
            'level' => 2
        ]);
    }
}
