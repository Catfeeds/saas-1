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
            'house_type' => $request->house_type,
            'public_private' => $request->public_private,
            'owner_info' => $request->owner_info,
            'name' => $request->name,
            'pedestal' => $request->pedestal,
            'pedestal_unit' => $request->pedestal_unit,
            'unit' => $request->unit,
            'unit_unit' => $request->unit_unit,
            'house_number' => $request->house_number,
            'grade' => $request->grade,
            'price' => $request->price,
            'price_unit' => $request->price_unit,
            'payment_type' => $request->payment_type,
            'increasing_situation_remark' => $request->increasing_situation_remark,
            'cost_detail' => $request->cost_detail,
            'acreage' => $request->acreage,
            'split' => $request->split,
            'mini_acreage' => $request->mini_acreage,
            'floor' => $request->floor,
            'total_floor' => $request->total_floor,
            'floor_height' => $request->floor_height,
            'property_grade' => $request->property_grade,
            'property_fee' => $request->property_fee,
            'register_company' => $request->register_company,
            'type' => $request->type,
            'orientation' => $request->orientation,
            'renovation' => $request->renovation,
            'open_bill' => $request->open_bill,
            'station_number' => $request->station_number,
            'rent_free' => $request->rent_free,
            'support_facilities' => $request->support_facilities,
            'source' => $request->source,
            'status' => $request->status,
            'shortest_lease' => $request->shortest_lease,
            'remarks' => $request->remarks,
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