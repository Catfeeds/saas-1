<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Area;
use App\Models\Company;
use App\Models\Customer;
use App\Models\MediaCustomer;
use App\Models\MediaUser;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateCustomer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateCustomer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移客源数据';

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
        $company_guid = Company::where('name', '楚楼网')->value('guid');
        // 只转移公司客源
        $guardian = MediaUser::where('ascription_store', '!=',6)->pluck('id')->toArray();
        $customer = MediaCustomer::whereNotIn('guardian', $guardian)->whereNull('deleted_at')->get();
        $data = [];
        foreach ($customer as $v) {
            $user_guid = $user = User::where('tel', $v->user->tel)->value('guid');
            $area = Area::where('id', $v->area_id)->value('name');
            $res = Customer::create([
                'guid' => Common::getUuid(),
                'company_guid' => $company_guid,
                'level' => $v->class,
                'guest' => 2,
                'customer_info' => [["name" => $v->name, "tel" => $v->tel]],
                'remarks' => $v->customer_note,
                'intention' => $area? [$area] : [],
                'house_type' => $v->room ?[$v->room.'室'] : [] ,
                'min_price' => $v->price_low,
                'max_price' => $v->price_high,
                'min_acreage' => $v->acre_low,
                'max_acreage' => $v->acre_high,
                'type' => $v->office_building_type,
                'renovation' => $v->renovation,
                'status' => $v->status == 1 ? 1: 7,
                'entry_person' => $user_guid,
                'guardian_person' => $user_guid,
                'invalid_reason' => $v->status != 1 ? '系统导入数据': '',
                'track_time' => $v->start_track_time? date('Y-m-d H:i:s', $v->start_track_time) : $v->created_at
            ]);
            if (!$res) {
                $data[] = $v->id;
            };
        }
        return $data;
    }
}
