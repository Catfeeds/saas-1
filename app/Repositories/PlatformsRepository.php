<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\House;
use App\Models\HouseShareRecord;
use Illuminate\Database\Eloquent\Model;

class PlatformsRepository extends Model
{
    // 平台新增房源
    public function addHouse($request)
    {
        \DB::beginTransaction();
        try {
            $house = House::create([
                'guid' => Common::getUuid(),
                'house_type' => $request->type,
                'house_identifier' => 'WH-'.time().rand(1,1000),
                'owner_info' => $request->owner_info,//业主电话
                'floor' => $request->floor,//所在楼层
                'house_number' => $request->house_number,//房号
                'building_block_guid' => $request->building_block_guid,//楼座guid
                'grade' => $request->grade,//房源等级
                'public_private' => 2,//盘别
                'price' => $request->price,//租金
                'price_unit' => $request->price_unit,//租金单位
                'payment_type' => $request->payment_type,//付款方式
                'increasing_situation_remark' => $request->increasing_situation_remark,//递增情况
                'cost_detail' => Common::arrayToObject($request->cost_detail),//费用明细
                'acreage' => $request->acreage,//面积
                'split' => $request->split,//可拆分
                'mini_acreage' => $request->mini_acreage,//最小面积
                'floor_height' => $request->floor_height,//层高
                'register_company' => $request->register_company,//注册公司
                'type' => $request->type,//写字楼类型
                'orientation' => $request->orientation,//朝向
                'renovation' => $request->renovation,//装修
                'open_bill' => $request->open_bill,//可开发票
                'station_number' => $request->station_number,//工位数量
                'rent_free' => $request->rent_free,//免租期
                'support_facilities' => Common::arrayToObject($request->support_facilities),//配套
                'source' => $request->source,//渠道来源
                'actuality' => $request->actuality,//现状
                'shortest_lease' => $request->shortest_lease,//最短租期
                'remarks' => $request->remarks,//备注
                'share' => 1,
                'release_source' => '平台',
                'indoor_img' => $request->indoor_img,
                'outdoor_img' => $request->outdoor_img,
                'house_type_img' => $request->house_type_img,
                'entry_person' => Common::admin()->guid,
                'guardian_person' => Common::admin()->guid,
                'track_time' => date('Y-m-d H:i:s',time())  // 第一次跟进时间
            ]);

            if (!$house) throw new \Exception('房源添加失败');
            $record = HouseShareRecord::create([
                'guid' => Common::getUuid(),
                'house_guid' => $house->guid,
                'remarks' => '平台 发布共享'
            ]);
            if (!$record) throw new \Exception('房源共享记录添加失败');
            \DB::commit();
            return $house;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('房源发布失败'.$exception->getMessage());
            return false;
        }
    }

    // 编辑房源
    public function updateHouse($request, $house)
    {
        $house->owner_info = $request->owner_info;
        $house->floor = $request->floor;
        $house->house_number = $request->house_number;
        $house->building_block_guid = $request->building_block_guid;
        $house->grade = $request->grade;
        $house->price = $request->price;
        $house->price_unit = $request->price_unit;
        $house->payment_type = $request->payment_type;
        $house->increasing_situation_remark = $request->increasing_situation_remark;
        $house->cost_detail = Common::arrayToObject($request->cost_detail);
        $house->acreage = $request->acreage;
        $house->split = $request->split;
        $house->mini_acreage = $request->mini_acreage;
        $house->floor_height = $request->floor_height;
        $house->register_company = $request->register_company;
        $house->type = $request->type;
        $house->orientation = $request->orientation;
        $house->renovation = $request->renovation;
        $house->open_bill = $request->open_bill;
        $house->station_number = $request->station_number;
        $house->rent_free = $request->rent_free;
        $house->support_facilities = Common::arrayToObject($request->support_facilities);
        $house->source = $request->source;
        $house->actuality = $request->actuality;
        $house->shortest_lease = $request->shortest_lease;
        $house->remarks = $request->remarks;
        $house->guardian_person = Common::admin()->guid;
        if (!$house->save()) return false;
        return true;
    }

}