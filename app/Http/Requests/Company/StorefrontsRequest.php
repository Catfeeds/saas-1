<?php

namespace App\Http\Requests\Company;

use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorefrontsRequest extends FormRequest
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
                    'area_guid.in' => '片区必须存在',
                    'user_guid.in' => '成员必须存在'
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
                    'area_guid' => [
                        'nullable',
                        Rule::in(
                            Area::all()->pluck('guid')->toArray()
                        )
                    ],
                    'user_guid' => [
                        'nullable',
                        'array',
                        Rule::in(
                            User::all()->pluck('guid')->toArray()
                        )
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
