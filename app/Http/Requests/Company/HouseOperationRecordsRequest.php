<?php

namespace App\Http\Requests\Company;

use App\Models\House;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HouseOperationRecordsRequest extends FormRequest
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
        switch ($this->route()->getActionMethod()) {
            case 'index':
                return [
                    'house_guid.exists' => '房源必须存在'
                ];
            default;
                return [

                ];
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'index':
                return [
                    'house_guid' => [
                        'required',
                        'max:32',
                        Rule::in(
                            House::all()->pluck('guid')->toArray()
                        )
                    ],
                    'type' => 'nullable|integer|between:1,6'
                ];
                default;
                return [

                ];
        }
    }
}
