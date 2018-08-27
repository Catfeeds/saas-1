<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class HousesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    // 验证字段
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'owner_info' => 'required|json',
                    'floor' => 'required|integer|between:1,99',
                    'name' => 'required|max:32',
                    'house_number' => 'required|max:64|numeric',
                    'building_block_guid' => 'required',
                    'grade' => 'required|integer|between:1,3',
                    'public_private' => 'required|integer|between:1,2',
                    'price' => 'required|numeric|max:9999999',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|json',
                    'acreage' => 'required|max:9999999',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|numeric|max:9999999',
                    'total_floor' => 'nullable|integer|numeric|between:1,99',
                    'floor_height' => 'nullable|numeric|max:9999999',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' =>'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|numeric',
                    'rent_free' => 'nullable|numeric',
                    'support_facilities' => 'nullable|json',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:300',



                    'house_type' => 'integer|between:1,8',
                    'house_type_img' => 'nullable|json',
                    'indoor_img' => 'nullable|json',
                    'outdoor_img' => 'nullable|json',
                    'entry_person' =>  'nullable|max:32',
                    'guardian_person' => 'nullable|max:255',
                    'pic_person' => 'nullable|max:255',
                    'key_person' => 'nullable|max:255',
                    'client_person' => 'nullable|max:255',
                ];
            case 'update':
                return [
                    'owner_info' => 'required|json',
                    'floor' => 'required|integer|between:1,99',
                    'name' => 'required|max:32',
                    'house_number' => 'required|max:64|numeric',
                    'building_block_guid' => 'required',
                    'grade' => 'required|integer|between:1,3',
                    'public_private' => 'required|integer|between:1,2',
                    'price' => 'required|numeric|max:9999999',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|json',
                    'acreage' => 'required|max:9999999',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|numeric|max:9999999',
                    'total_floor' => 'nullable|integer|numeric|between:1,99',
                    'floor_height' => 'nullable|numeric|max:9999999',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' =>'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|numeric',
                    'rent_free' => 'nullable|numeric',
                    'support_facilities' => 'nullable|json',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:300',




                    'house_type' => 'integer|between:1,8',
                    'house_type_img' => 'nullable|json',
                    'indoor_img' => 'nullable|json',
                    'outdoor_img' => 'nullable|json',
                    'entry_person' =>  'nullable|max:32',
                    'guardian_person' => 'nullable|max:255',
                    'pic_person' => 'nullable|max:255',
                    'key_person' => 'nullable|max:255',
                    'client_person' => 'nullable|max:255',
                ];
            default:
                {
                   return [];
                }
        }
    }
}
