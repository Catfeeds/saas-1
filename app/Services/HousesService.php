<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\BuildingBlock;
use App\Models\House;
use App\Models\User;

class HousesService
{
    // 通过楼座,楼层获取房源成功
    public function adoptConditionGetHouse(
        $request
    )
    {
        // 通过楼座,楼层获取房号
        $housesNumber = House::where([
//            'company_guid' => Common::user()->guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor
        ])->pluck('house_number');

        if (empty($housesNumber)) return [];

        // 获取楼盘楼座拼接
        $buildingBlock = BuildingBlock::where('guid', $request->building_block_guid)->with('building')->first();

        // 楼座名称
        $buildingBlockName = $buildingBlock->name.$buildingBlock->name_unit.'-'.$buildingBlock->unit.$buildingBlock->unit_unit;

        return [
            'name' => $buildingBlock->building->name.$buildingBlockName,
            'housesNumber' => $housesNumber
        ];
    }

    // 房号验证
    public function houseNumberValidate(
        $request
    )
    {
        $house = House::where([
            'company_guid' => Common::user()->guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor,
            'house_number' => $request->house_number
        ])->with('buildingBlock.building')->first();

        if (empty($house)) return [];

        return [
            'house_img' => $house->indoor_img_cn,
            'buildingName' => $house->buildingBlock->building->name,
            'acreage' => $house->acreage_cn,
            'price' => $house->price.$house->price_unit_cn,
            'entry_person' => User::find($house->entry_person)->name,
            'created_at' => $house->created_at->format('Y-m-d H:i')
        ];
    }

    //提取数据
    public function getData($res)
    {
        $houses = [];
        $houses['guid'] = $res->guid;
        $houses['img'] = $res->indoor_img_cn; //图片
        $houses['name'] = $res->buildingBlock->building->name;  //名称
        $houses['public_private'] = $res->public_private_cn; //公私盘
        $houses['grade'] = $res->grade_cn; //级别
        $houses['key'] = $res->key ? true : false; //是否有钥匙
        $houses['price_unit'] = $res->price . $res->price_unit_cn; //价格单位
        $houses['payment_type'] = $res->payment_type_cn; //付款方式
        $houses['acreage'] = $res->acreage_cn; //面积
        $houses['renovation'] = $res->renovation_cn;  //装修程度
        $houses['orientation'] = $res->orientation_cn; //朝向
        $houses['type'] = $res->type_cn; //类型
        $houses['floor'] = $res->floor. '层'; //楼层
        $houses['total_floor'] = '共' . $res->total_floor . '层'; //总楼层
        $houses['top'] = $res->top == 1 ? true : false; // 置顶
        $houses['track_user'] = !$res->track->isEmpty() ? $res->track->sortByDesc('created_at')->first()->user->name : $res->entryPerson->name;
        $houses['track_time'] = $res->track_time; //跟进时间
        return $houses;
    }



}