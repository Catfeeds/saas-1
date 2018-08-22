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
                    'house_type' => 'integer|between:1,8',
                    'public_private' => 'required|integer|between:1,2',
                    'owner_info' => 'required|json',
                    'name' => 'required|max:32',
                    'pedestal' => 'required|max:16',
                    'pedestal_unit' => 'nullable|integer|between:1,6',
                    'unit' => 'required|max:128',
                    'unit_unit' => 'integer|max:128',
                    'house_number' => 'required|max:64',
                    'grade' => 'required|integer|between:1,3',
                    'price' => 'required',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|json',
                    'acreage' => 'required|max:32',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|max:32',
                    'floor' => 'required|integer|between:1,99',
                    'total_floor' => 'nullable|integer|between:1,99',
                    'floor_height' => 'nullable',
                    'property_grade' => 'nullable|integer|between:1,4',
                    'property_fee' => 'nullable|max:10',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' =>'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|max:32',
                    'rent_free' => 'nullable|integer|max:11',
                    'support_facilities' => 'nullable|json',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:9999',
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
                    'house_type' => 'integer|between:1,8',
                    'public_private' => 'required|integer|between:1,2',
                    'owner_info' => 'required|json',
                    'name' => 'required|max:32',
                    'pedestal' => 'required|max:16',
                    'pedestal_unit' => 'nullable|integer|between:1,6',
                    'unit' => 'required|max:128',
                    'unit_unit' => 'integer|max:128',
                    'house_number' => 'required|max:64',
                    'grade' => 'required|integer|between:1,3',
                    'price' => 'required',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|json',
                    'acreage' => 'required|max:32',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|max:32',
                    'floor' => 'required|integer|between:1,99',
                    'total_floor' => 'nullable|integer|between:1,99',
                    'floor_height' => 'nullable',
                    'property_grade' => 'nullable|integer|between:1,4',
                    'property_fee' => 'nullable|max:10',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' =>'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|max:32',
                    'rent_free' => 'nullable|integer|max:11',
                    'support_facilities' => 'nullable|json',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:9999',
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
