<?php

namespace App\Http\Requests\Company;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompaniesRequest extends FormRequest
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
            case 'store':
                return [
                    'name.unique' => '公司名称不能重复添加',
                    'tel.not_in' => '用户电话不能重复'
                ];
            case 'update':
                return [
                    'name.not_in' => '公司名称不能重复添加',
                    'tel.not_in' => '用户电话不能重复'
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
            case 'store':
                return [
                    'name' => 'required|max:128|unique:companies',
                    'slogan' => 'nullable|max:512',
                    'license' => 'nullable|max:512',
                    'address' => 'nullable|max:256',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::notIn(
                            User::all()->pluck('tel')->toArray()
                        )
                    ],
                    'password' => 'required|min:6|max:18',
                ];
            case 'update':
                return [
                    'name' => 'required|max:128|unique:companies',
                    'slogan' => 'nullable|max:512',
                    'license' => 'nullable|max:512',
                    'address' => 'nullable|max:256',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::notIn(
                            User::all()->pluck('tel')->toArray()
                        )
                    ],
                    'password' => 'required|min:6|max:18',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
