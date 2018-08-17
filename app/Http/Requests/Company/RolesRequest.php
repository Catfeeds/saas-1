<?php

namespace App\Http\Requests\Company;

use App\Handler\Common;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RolesRequest extends FormRequest
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
                    'name.unique' => '同公司角色不能重复'
                ];
            case 'update':
                return [
                    'name.not_in' => '同公司角色不能重复'
                ];
            default:
                {
                    return [];
                }
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
                    'name' => [
                        'required',
                        'max:32',
                        Rule::unique('roles')->where(function($query) {
                            $query->where('company_guid', Common::user()->company_guid);
                        })
                    ],
                    'level' => [
                        'required',
                        'integer',
                        'between:1,5',
                    ]
                ];
            case 'update':
            case 'updateRoleName':
                return [
                    'name' => [
                        'max:32',
                        Rule::notIn(
                            Role::where('company_guid',$this->company_guid)->pluck('name')->toArray()
                        )
                    ],
                ];
            case 'updateRoleLevel':
                return [
                    'level' => [
                        'integer',
                        'between:1,5',
                    ]
                ];
            default:
                {
                    return [];
                }
        }
    }
}
