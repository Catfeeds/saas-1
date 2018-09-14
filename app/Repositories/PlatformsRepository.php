<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\House;
use Illuminate\Database\Eloquent\Model;

class PlatformsRepository extends Model
{
    // 平台新增房源
    public function addHouse($request)
    {
        return House::create([
            'guid' => Common::getUuid(),
            'house_type' => 1,
            'house_identifier' => 'WH-'.time().rand(1,1000),
            'owner_info' => $request->owner_info,//业主电话
            'floor' => $request->floor,//所在楼层
            'house_number' => $request->house_number,//房号
            'building_block_guid' => $request->building_block_guid,//楼座guid
            'grade' => $request->grade,//房源等级
            'public_private' => $request->public_private,//盘别
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
    }


}