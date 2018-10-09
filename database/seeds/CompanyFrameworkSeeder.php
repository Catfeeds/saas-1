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
        $company = Company::where('name', '楚楼网')->first();
        CompanyFramework::create([
            'guid' => Common::getUuid(),
            'name' => '光谷总部时代',
            'company_guid' => $company->guid,
            'level' => 2
        ]);
    }
}
