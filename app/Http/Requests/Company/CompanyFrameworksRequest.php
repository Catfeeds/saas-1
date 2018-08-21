<?php

namespace App\Http\Requests\Company;

use App\Models\CompanyFramework;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyFrameworksRequest extends FormRequest
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
            case 'addArea':
                return [
                    'storefront_guid.*.in' => '请添加存在的门店'
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
            case 'addArea':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ],
                    'storefront_guid' => 'array',
                    'storefront_guid.*' => [
                        Rule::in(
                            CompanyFramework::where(['level' => 2, 'company_guid' => 'asdasdas'])->pluck('guid')->toArray()
                        )
                    ]
                ];
            case 'addStorefront':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ]
                ];
            case 'addGroup':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ],
                    'parent_guid' => 'required'
                ];
            default;
                return [

                ];
        }
    }
}
