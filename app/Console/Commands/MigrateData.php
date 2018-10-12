<?php

namespace App\Console\Commands;

use App\Models\CompanyFramework;
use App\Models\Customer;
use App\Models\House;
use App\Models\HouseImgRecord;
use App\Models\Role;
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
    protected $description = '修改总部时代数据';

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
        // 更改人员公司
        $companyFramework = CompanyFramework::where('name', '光谷总部时代')->first();
        $user = User::where('rel_guid', $companyFramework->guid)->get();
           foreach ($user as $v) {
               // 查询角色id
               $role_guid = Role::where([
                   'company_guid' => $companyFramework->company_guid,
                   'level' => $v->role->level
               ])->value('guid');
               $suc = $v->update([
                   'company_guid' => $companyFramework->company_guid,
                   'role_guid' => $role_guid
               ]);
               if (!$suc) \Log::info($v->guid.'变更失败');
        }

        // 房源变更
        $user = User::where('rel_guid', $companyFramework->guid)->pluck('guid')->toArray();

        // 变更房子
        $house = House::whereIn('guardian_person', $user)->update(['company_guid' => $companyFramework->company_guid]);
        if (!$house) {
            return '房源变更失败';
        }

        // 变更客户
        $customer = Customer::whereIn('guardian_person', $user)->update(['company_guid' => $companyFramework->company_guid]);
        if (!$customer) {
            return '客源变更失败';
        }
    }
}
