<?php
namespace App\Repositories;

use App\Handler\Common;
use App\Models\House;
use Illuminate\Database\Eloquent\Model;

class HousesRepository extends Model
{
    // 添加房源
    public function addHouse($request)
    {
        return House::create([
            'guid' => Common::getUuid(),
            'owner_info' => $request->owner_info,//业主电话
            'floor' => $request->floor,//所在楼层
            'name' => $request->name,//小区
            'house_number' => $request->house_number,//房号
            'building_block_guid' => $request->building_block_guid,//楼座guid
            'grade' => $request->grade,//房源等级
            'public_private' => $request->public_private,//盘别
            'price' => $request->price,//租金
            'price_unit' => $request->price_unit,//租金单位
            'payment_type' => $request->payment_type,//付款方式
            'increasing_situation_remark' => $request->increasing_situation_remark,//递增情况
            'cost_detail' => $request->cost_detail,//费用明细
            'acreage' => $request->acreage,//面积
            'split' => $request->split,//可拆分
            'mini_acreage' => $request->mini_acreage,//最小面积
            'total_floor' => $request->total_floor,//总楼层
            'floor_height' => $request->floor_height,//层高
            'register_company' => $request->register_company,//注册公司
            'type' => $request->type,//写字楼类型
            'orientation' => $request->orientation,//朝向
            'renovation' => $request->renovation,//装修
            'open_bill' => $request->open_bill,//可开发票
            'station_number' => $request->station_number,//工位数量
            'rent_free' => $request->rent_free,//免租期
            'support_facilities' => $request->support_facilities,//配套
            'source' => $request->source,//渠道来源
            'status' => $request->status,//现状
            'shortest_lease' => $request->shortest_lease,//最短租期
            'remarks' => $request->remarks,//备注







            'house_type' => $request->house_type,
            'pedestal' => $request->pedestal,
            'pedestal_unit' => $request->pedestal_unit,
            'unit' => $request->unit,
            'unit_unit' => $request->unit_unit,
            'property_grade' => $request->property_grade,
            'property_fee' => $request->property_fee,
            'house_type_img' => $request->house_type_img,
            'indoor_img' => $request->indoor_img,
            'outdoor_img' => $request->outdoor_img,
            'entry_person' => $request->entry_person,
            'guardian_person' => $request->guardian_person,
            'pic_person' => $request->pic_person,
            'key_person' => $request->key_person,
            'client_person' => $request->client_person,
        ]);
    }
}