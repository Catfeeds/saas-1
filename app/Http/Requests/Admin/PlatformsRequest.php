<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlatformsRequest extends FormRequest
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'owner_info' => 'required|array',
                    'floor' => 'required|integer',
                    'house_number' => 'required|numeric',
                    'building_block_guid' => 'required',
                    'grade' => 'required|integer|between:1,3',
                    'price' => 'required|numeric|max:9999999',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|array',
                    'acreage' => 'required|max:9999999',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|numeric|max:9999999',
                    'floor_height' => 'nullable|numeric|max:9999999',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' => 'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|numeric',
                    'rent_free' => 'nullable|numeric',
                    'support_facilities' => 'nullable|array',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:300',
                ];
            case 'update':
                return [
                    'owner_info' => 'required|array',
                    'floor' => 'required|integer',
                    'house_number' => 'required|numeric',
                    'building_block_guid' => 'required',
                    'grade' => 'required|integer|between:1,3',
                    'price' => 'required|numeric|max:9999999',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|array',
                    'acreage' => 'required|max:9999999',
                    'split' => 'nullable|integer|between:1,2',
                    'mini_acreage' => 'nullable|numeric|max:9999999',
                    'floor_height' => 'nullable|numeric|max:9999999',
                    'register_company' => 'nullable|integer|between:1,2',
                    'type' => 'nullable|integer|between:1,5',
                    'orientation' => 'nullable|integer|between:1,10',
                    'renovation' => 'nullable|integer|between:1,5',
                    'open_bill' => 'nullable|integer|between:1,2',
                    'station_number' => 'nullable|numeric',
                    'rent_free' => 'nullable|numeric',
                    'support_facilities' => 'nullable|array',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:300',
                 ];
            default:
                {
                    return [];
                }
        }
    }
}

