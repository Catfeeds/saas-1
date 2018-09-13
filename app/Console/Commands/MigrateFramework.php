<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\Company;
use App\Models\House;
use App\Models\MediaBuilding;
use App\Models\MediaBuildingBlock;
use App\Models\OfficeBuildingHouse;
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
        \DB::beginTransaction();
        try {
            // 添加公司
            $company = Company::create([
                'guid' => Common::getUuid(),
                'name' => '楚楼网',
                'city_guid' => '134dba069ad211e8b2e4144fd7c018f6',
                'area_guid' => '13cc70129ad211e8b005144fd7c018f6',
                'address' => '光谷智慧园',
                'company_tel' => '400-580-888'
            ]);
            if (!$company) \Log::info('公司添加失败');

            $user = User::create([
                'guid' => Common::getUuid(),
                'company_guid' => $company->guid,
                'name' => ''
            ]);



        } catch (\Exception $exception) {

        }


    }
}
