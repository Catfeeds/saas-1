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
                        Rule::in(CompanyFramework::all()->pluck('guid')->toArray())
                    ]
                ];
            case 'addStorefront':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            CompanyFramework::all()->pluck('name')->toArray()
                        )
                    ]
                ];
            case 'addGroup':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            CompanyFramework::all()->pluck('name')->toArray()
                        )
                    ],
                    'parent_guid' => 'required'
                ];
            default;
                return [

                ];
        }
    }
}
