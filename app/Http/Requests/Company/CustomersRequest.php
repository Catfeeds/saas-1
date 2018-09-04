<?php

namespace App\Http\Requests\Company;

use App\Handler\Common;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomersRequest extends FormRequest
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

    public function messages()
    {
        return [

        ];
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
                    'level' =>'nullable|integer|between:1,3',
                    'guest' => 'required|integer|between:1,2',
                    'customer_info' => 'required',
                    'remarks' => 'nullable',
                    'intention' => 'nullable',
                    'block' => 'nullable',
                    'building' => 'nullable',
                    'house_type' => 'nullable',
                    'min_price' => 'nullable',
                    'max_price' => 'nullable',
                    'min_acreage' => 'nullable',
                    'max_acreage' => 'nullable',
                    'type' => 'nullable|integer|between:1,5',
                    'renovation' => 'nullable|integer',
                    'min_floor' => 'nullable',
                    'max_floor' => 'nullable',
                    'track_time' => 'nullable'
                ];
                break;
            case 'update':
                return [
                    'level' =>'nullable|integer|between:1,3',
                    'guest' => 'required|integer|between:1,2',
                    'customer_info' => 'required',
                    'remarks' => 'nullable',
                    'intention' => 'nullable',
                    'block' => 'nullable',
                    'building' => 'nullable',
                    'house_type' => 'nullable',
                    'min_price' => 'nullable',
                    'max_price' => 'nullable',
                    'min_acreage' => 'nullable',
                    'max_acreage' => 'nullable',
                    'type' => 'nullable|integer|between:1,5',
                    'renovation' => 'nullable|integer',
                    'min_floor' => 'nullable',
                    'max_floor' => 'nullable',
                    'track_time' => 'nullable'
                ];
           case 'invalid':
                 return [
                   'customer_guid' => 'required|exists:customer,guid',
                 ];
            case 'updateGuest':
                return [
                    'guid' => 'required|exists:customer,guid'
                ];
                default;
                return[
                ];
            case 'transfer':
                return [
                    'broker' => [
                        'nullable',
                        Rule::in(
                            User::where(['company_guid' => Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ],
                    'entry_person' => [
                        'nullable',
                        Rule::in(
                            User::where(['company_guid' => Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ],
                    'guardian_person' => [
                        'nullable',
                        Rule::in(
                            User::where(['company_guid' => Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ]
                ];
            case 'getCustomersInfo':
                return [
                    'guid' =>[
                    'required',
                    Rule::in(
                        Customer::all()->pluck('guid')->toArray()
                    )
                ],
            ];
        }
    }
}
