<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\House;
use App\Models\User;
use Illuminate\Console\Command;

class addHouse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'add:house';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '添加测试房源';

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
        $users = User::where(['company_guid' => 'qweqwe'])->get();
        $house_number = 101;

        foreach ($users as $user) {

            $info = [[
                "name" => $user->name,
                "tel" => $user->tel,
                "remarks" => $user->name.':'.$user->tel,
            ]];
            House::create([
                'guid' => Common::getUuid(),

                'public_private' => 1, // 公盘

                'building_block_guid' => '5446885ab65011e885ae3d01f340c4f8',//楼座guid

                'house_type' => 1,

                'company_guid' => 'qweqwe',

                'floor' => 100, //所在楼层

                'house_number' => $house_number,//房号

                'house_identifier' => 'WH-'.time().rand(1,1000),

                'owner_info' => $info,//业主电话

                'grade' => 1,//房源等级

                'price' => '100',//租金

                'price_unit' => 1,//租金单位

                'payment_type' => 1,//付款方式

                'increasing_situation_remark' => null,//递增情况

                'cost_detail' => [], //费用明细

                'acreage' => '100', //面积
                'split' => null,//可拆分
                'mini_acreage' => null,//最小面积
                'floor_height' => null,//层高
                'register_company' => null,//注册公司
                'type' => null,//写字楼类型
                'orientation' => null,//朝向
                'renovation' => null,//装修
                'open_bill' => null,//可开发票
                'station_number' => null,//工位数量
                'rent_free' => null,//免租期
                'support_facilities' => [],//配套
                'source' => null,//渠道来源
                'actuality' => null,//现状
                'shortest_lease' => null,//最短租期
                'remarks' => null,//备注
                'entry_person' => $user->guid,
                'guardian_person' => $user->guid,
                'track_time' => date('Y-m-d H:i:s',time())  // 第一次跟进时间
            ]);
            ++ $house_number;
            House::create([
                'guid' => Common::getUuid(),

                'public_private' => 2, // 公盘

                'building_block_guid' => '5446885ab65011e885ae3d01f340c4f8',//楼座guid

                'house_type' => 1,

                'company_guid' => 'qweqwe',

                'floor' => 100, //所在楼层

                'house_number' => $house_number,//房号

                'house_identifier' => 'WH-'.time().rand(1,1000),

                'owner_info' => $info,//业主电话

                'grade' => 1,//房源等级

                'price' => '100',//租金

                'price_unit' => 1,//租金单位

                'payment_type' => 1,//付款方式

                'increasing_situation_remark' => null,//递增情况

                'cost_detail' => [], //费用明细

                'acreage' => '100', //面积
                'split' => null,//可拆分
                'mini_acreage' => null,//最小面积
                'floor_height' => null,//层高
                'register_company' => null,//注册公司
                'type' => null,//写字楼类型
                'orientation' => null,//朝向
                'renovation' => null,//装修
                'open_bill' => null,//可开发票
                'station_number' => null,//工位数量
                'rent_free' => null,//免租期
                'support_facilities' => [],//配套
                'source' => null,//渠道来源
                'actuality' => null,//现状
                'shortest_lease' => null,//最短租期
                'remarks' => null,//备注
                'entry_person' => $user->guid,
                'guardian_person' => $user->guid,
                'track_time' => date('Y-m-d H:i:s',time())  // 第一次跟进时间
            ]);
            ++ $house_number;
        }

    }
}
