<?php

namespace App\Console\Commands;

use App\Handler\Common;
use App\Models\Building;
use App\Models\BuildingBlock;
use App\Models\Company;
use App\Models\House;
use App\Models\MediaBuilding;
use App\Models\MediaBuildingBlock;
use App\Models\MediaUser;
use App\Models\OfficeBuildingHouse;
use App\Models\User;
use Illuminate\Console\Command;

class MigrateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrateHouse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '迁移房源数据';

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
        $data = [];
        //查询公司的全部房子
        $guardian = MediaUser::where('ascription_store',6)->pluck('id')->toArray();
        $house = OfficeBuildingHouse::with('buildingBlock','buildingBlock.building','user')->whereIn('guardian', $guardian)->get();
        foreach ($house as $v) {
            // 楼座guid
            $building_block_guid = BuildingBlock::where('id', $v->building_block_id)->value('guid');

            // 查询新表的人员对应的guid
            $user = User::where('tel', $v->user->tel)->value('guid');
            $pic_person = '';
            if ($v->house_type_img || $v->indoor_img) {
                $pic_person = $user;
            }
            // 插入新房源表
            //备注
            $remarks = '';
            //6室1厅付佣:50%,看房时间电话预约状态:委托

            // 几室几厅
            if ($v->HouseType) {
                $remarks .= $v->HouseType;
            }
            // 房源描述
            if ($v->house_description) {
                $remarks .= ',房源描述:'.$v->house_description;
            }
            // 入住时间
            if ($v->check_in_time) {
                $remarks .=',入住时间:'. $v->check_in_time;
            }
            // 付佣
            if ($v->pay_commission) {
                $remarks .=',付佣:'.$v->pay_commission.'%';
            }
            // 实勘
            if ($v->prospecting == 1) {
                $remarks .= ',已实勘';
            }
            // 看房时间
            if ($v->see_house_time) {
                $remarks .=',看房时间:'.$v->see_house_time_cn;
            }
            // 房源状态
            if ($v->house_proxy_type == 1) {
                $remarks .=',状态:独家';
            } else {
                $remarks .=',状态:委托';
            }
            $remarks = trim($remarks, ',');
            // 递增情况
            $increasing = '';
            if ($v->increasing_situation) {
                $increasing .= $v->increasing_situation. '%';
            }
            if ($v->increasing_situation_remark) {
                $increasing .=','.$v->increasing_situation_remark;
            }
            $increasing = trim($increasing, ',');

            // 免租期
            $rent_free = null;
            if ($v->rent_free && $v->rent_free != 12) {
                $rent_free = $v->rent_free * 30;
            }

            // 获取最后一条数据
            $lastHouse = House::orderBy('created_at', 'asc')->get()->last();
            // 房源编号
            $houseIdentifier = Common::identifier($lastHouse);

            $res = House::create([
                'guid' => Common::getUuid(),
                'company_guid' => $company_guid,
                'house_identifier' => $houseIdentifier,
                'house_type' => 1,
                'owner_info' => $v->owner_info,
                'building_block_guid' => $building_block_guid,
                'floor' => $v->floor,
                'house_number' => $v->house_number,
                'grade' => 1,
                'public_private' => 1,
                'price' => $v->unit_price,
                'payment_type' => $v->payment_type,
                'increasing_situation_remark' => $increasing,
                'cost_detail' => $v->cost_detail,
                'acreage' => $v->constru_acreage,
                'mini_acreage' => $v->min_acreage,
                'split' => $v->split,
                'register_company' => $v->register_company,
                'type' => $v->office_building_type,
                'orientation' => $v->orientation,
                'renovation' => $v->renovation,
                'open_bill' => $v->open_bill,
                'station_number' => $v->station_number,
                'rent_free' => $rent_free,
                'support_facilities' => $v->support_facilities,
                'shortest_lease' => $v->shortest_lease,
                'house_type_img' => $v->house_type_img,
                'indoor_img' => $v->indoor_img,
                'entry_person' => $user,
                'guardian_person' => $user,
                'pic_person' => $pic_person,
                'status' => $v->house_busine_state == 1 ? 1: 7,
                'top' => 2, // 默认不置顶
                'track_time' => $v->start_track_time? date('Y-m-d H:i:s', $v->start_track_time) : $v->created_at,
                'remarks' => $remarks
            ]);
            if (!$res) $data[] = $v->id;
        }
        return $data;
    }
}
