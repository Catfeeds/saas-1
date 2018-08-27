<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\BuildingBlock;
use App\Models\House;

class HousesService
{
    // 通过楼座,楼层获取房源成功
    public function adoptConditionGetHouse(
        $request
    )
    {
        // 通过楼座,楼层获取房号
        $housesNumber = House::where([
            'company_guid' => Common::user()->guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor
        ])->pluck('house_number');

        // 获取楼盘楼座拼接
        $buildingBlock = BuildingBlock::where('guid', $request->building_block_guid)->with('building')->first();

        // 楼座名称
        $buildingBlockName = $buildingBlock->name.$buildingBlock->name_unit.'-'.$buildingBlock->unit.$buildingBlock->unit_unit;

        return [
            'name' => $buildingBlock->building->name.$buildingBlockName,
            'housesNumber' => $housesNumber
        ];
    }

    public function houseNumberValidate(
        $request
    )
    {
        $house = House::where([
            'company_guid' => Common::user()->guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor,
            'house_number' => $request->house_number
        ])->first();




    }


}