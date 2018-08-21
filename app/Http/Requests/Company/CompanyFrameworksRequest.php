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
            case 'store':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::notIn(
                            CompanyFramework::all()->pluck('name')->toArray()
                        )
                    ]
                ];
            default;
                return [

                ];
        }
    }
}
