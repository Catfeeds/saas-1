<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Customer;
use App\Models\House;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '公司数据迁移';

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
        // 更新黄建公司的房子
        $company_guid = Company::where('name', '汉口区域')->value('guid');

        // 查询全部人员
        $user = User::where('company_guid', $company_guid)->pluck('guid')->toArray();

        // 更新房子
        House::whereIn('guardian_person', $user)->update(['company_guid' => $company_guid]);

        Customer::whereIn('guardian_person', $user)->update(['company_guid' => $company_guid]);


        // 更新程达公司的房子
        $company_guid = Company::where('name', '光谷区域')->value('guid');

        // 查询全部人员
        $user = User::where('company_guid', $company_guid)->pluck('guid')->toArray();

        // 更新房子
        House::whereIn('guardian_person', $user)->update(['company_guid' => $company_guid]);

        Customer::whereIn('guardian_person', $user)->update(['company_guid' => $company_guid]);
    }
}
