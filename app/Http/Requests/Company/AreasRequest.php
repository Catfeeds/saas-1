<?php

namespace App\Http\Requests\Company;

use App\Models\Storefront;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreasRequest extends FormRequest
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

    // 验证错误信息
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'storefronts_guid.in' => '门店必须存在'
                ];
            case 'update':
                return [

                ];
            default:
                {
                    return [];
                }
        }
    }

    // 字段验证
    public function rules()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'name' => 'required|max:32',
                    'storefronts_guid.*' => [
                        'nullable',
                        'array',
//                        Rule::in(
//                            Storefront::all()->pluck('guid')->toArray()
//                        )
                    ],
                ];
            case 'update':
                return [

                ];
            default:
                {
                    return [];
                }
        }
    }
}
