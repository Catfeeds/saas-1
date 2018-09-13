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
        $data = [];
        //查询全部的房子
        $house = OfficeBuildingHouse::with('buildingBlock','buildingBlock.building','user')->get();
        foreach ($house as $v) {
            // 房源对应的楼盘名称
            $building_name = $v->buildingBlock->building->name;
            // 查询对应的楼盘的guid
            $building_guid = Building::where('name', $building_name)->value('guid');
            // 房源对应栋座名称
            $building_block_name = $v->buildingBlock->name;
            // 房源对应楼座单位
            $name_unit = $v->buildingBlock->name_unit;
            // 房源对应单位名称
            $unit = $v->buildingBlock->unit;
            // 房源对应单位单元
            $unit_unit = $v->buildingBlock->unit_unit;
            // 通过楼盘和栋座信息确定楼栋guid
            $building_block_guid = BuildingBlock::where([
                'building_guid' => $building_guid,
                'name' => $building_block_name,
                'name_unit' => $name_unit,
                'unit' => $unit,
                'unit_unit' => $unit_unit
            ])->value('guid');
            // 查询新表的人员对应的guid
            $user = User::where('tel', $v->user->tel)->value('guid');
            $pic_person = '';
            if ($v->house_type_img || $v->indoor_img) {
                $pic_person = $user;
            }
            // 插入新房源表
            //备注
            $remarks = '';
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
                $remarks .=',入住时间'. $v->check_in_time;
            }
            // 付佣
            if ($v->pay_commission) {
                $remarks .='付佣:'.$v->pay_commission.'%';
            }
            // 实勘
            if ($v->prospecting == 1) {
                $remarks .= ',已实勘';
            }
            // 看房时间
            if ($v->see_house_time) {
                $remarks .=',看房时间'.$v->see_house_time_cn;
            }
            // 房源状态
            if ($v->house_proxy_type == 1) {
                $remarks .='状态:独家';
            } else {
                $remarks .='状态:委托';
            }
            $remarks = trim($remarks, ',');
            $res = House::create([
                'guid' => Common::getUuid(),
                'company_guid' => 'a3d2f99cb70111e8b97808002772f793',
                'house_identifier' => 'WH-'.time().rand(1,1000),
                'house_type' => 1,
                'owner_info' => $v->owner_info,
                'building_block_guid' => $building_block_guid,
                'floor' => $v->floor,
                'house_number' => $v->house_number,
                'grade' => 1,
                'public_private' => 1,
                'price' => $v->unit_price,
                'price_unit' => 2,
                'payment_type' => $v->payment_type,
                'increasing_situation_remark' => $v->increasing_situation.'%'. $v->increasing_situation_remark ? ','.$v->increasing_situation_remark:'',
                'cost_detail' => $v->cost_detail,
                'acreage' => $v->constru_acreage,
                'min_acreage' => $v->min_acreage,
                'split' => $v->split,
                'register_company' => $v->register_company,
                'type' => $v->office_building_type,
                'orientation' => $v->orientation,
                'renovation' => $v->renovation,
                'open_bill' => $v->open_bill,
                'station_number' => $v->station_number,
                'rent_free' => $v->rent_free == 12 ? '' : $v->rent_free*30,
                'support_facilities' => $v->support_facilities,
                'shortest_lease' => $v->shortest_lease,
                'house_type_img' => $v->house_type_img,
                'indoor_img' => $v->indoor_img,
                'entry_person' => $user,
                'guardian_person' => $user,
                'pic_person' => $pic_person,
                'status' => $v->house_busine_state == 1 ? 1: 7,
                'top' => 2, // 默认不置顶
                'track_time' => date('Y-m-d H:i:s', $v->start_track_time),
                'remarks' => $remarks
            ]);
            if (!$res) $data[] = $v->id;
        }
        return $data;
    }
}
