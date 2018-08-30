<?php

namespace App\Http\Requests\Company;

use App\Models\BuildingBlock;
use App\Models\CompanyFramework;
use App\Models\House;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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

    // 中文报错
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'changePersonnel':
                return [
                    'house_guid.in' => '房源必须存在',
                ];
            case 'transferHouse' :
                return [
                    'user_guid.in' => '用户必须存在',
                ];
            default;
                return [

                ];
        }
    }

    // 验证字段
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
                    'public_private' => 'required|integer|between:1,2',
                    'price' => 'required|numeric|max:9999999',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|array',
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
                    'public_private' => 'required|integer|between:1,2',
                    'price' => 'required|numeric|max:9999999',
                    'price_unit' => 'nullable|integer|between:1,3',
                    'payment_type' => 'required|integer|between:1,8',
                    'increasing_situation_remark' => 'nullable|max:256',
                    'cost_detail' => 'nullable|array',
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
                    'support_facilities' => 'nullable|array',
                    'source' => 'nullable|integer|between:1,7',
                    'status' => 'nullable|integer|between:1,3',
                    'shortest_lease' => 'nullable|integer|between:1,5',
                    'remarks' => 'nullable|max:300',
                ];
            case 'updateImg':
                return [
                    'house_type_img' => 'max:1024',
                    'indoor_img' => 'max:1024',
                    'outdoor_img' => 'max:1024',
                ];
            case 'changePersonnel':
                return [
                    'house_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            House::all()->pluck('guid')->toArray()
                        )
                    ],
                    'entry_person' =>  'nullable|max:32',
                    'guardian_person' => 'nullable|max:32',
                    'pic_person' => 'nullable|max:32',
                    'key_person' => 'nullable|max:32'
                ];
            case 'adoptConditionGetHouse':
                return [
                    'building_block_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            BuildingBlock::all()->pluck('guid')->toArray()
                        )
                    ],
                    'floor' => 'required|integer',
                ];
            case 'HouseNumberValidate':
                return [
                    'building_block_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            BuildingBlock::all()->pluck('guid')->toArray()
                        )
                    ],
                    'floor' => 'required|integer',
                    'house_number' => 'required|max:64',
                    'house_guid' => [
                        'nullable',
                        'max:32',
                        Rule::in(
                            House::all()->pluck('guid')->toArray()
                        )
                    ]
                ];
            case 'adoptAssociationGetHouse':
                return [
                  'building_block_guid' => [
                      'required',
                      'max:32',
                      Rule::in(
                          BuildingBlock::all()->pluck('guid')->toArray()
                      )
                  ],
                  'floor' => 'integer',
                ];
            case 'seeHouseWay':
                return [
                    'type' => 'required|integer|between:1,4',
                    'remarks' => 'nullable|max:128',
                    'storefront_guid' => [
                        'nullable',
                        'max:32',
                        Rule::in(
                            CompanyFramework::where('level',2)->pluck('guid')->toArray()
                        )
                    ],
                    'house_guid' => [
                        'nullable',
                        'max:32',
                        Rule::in(
                            House::all()->pluck('guid')->toArray()
                        )
                    ],
                    'key_number' => 'nullable|max:32',
                    'key_single' => 'nullable',
                    'received_time' => 'nullable'
                ];
            case 'transferHouse':
                return [
                    'user_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            User::all()->pluck('guid')->toArray()
                        )
                    ]
                ];
            default:
                {
                   return [];
                }
        }
    }
}
