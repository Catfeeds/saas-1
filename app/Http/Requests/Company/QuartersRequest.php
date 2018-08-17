<?php

namespace App\Http\Requests\Company;

use App\Handler\Common;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class QuartersRequest extends FormRequest
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
            case 'updateRoleName':
                return [
                    'name.unique' => '同公司角色不能重复'
                ];
            case 'updateRolePermission':
                return [
                    'role_guid.exists' => '岗位必须存在',
                    'permission_guid.exists' => '权限必须存在'
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
            case 'updateRoleName':
                return [
                    'name' => [
                        'max:32',
                        Rule::unique('roles')->where(function($query) {
                            $query->where('company_guid', Common::user()->company_guid);
                        })->ignore($this->guid, 'guid')
                    ],
                ];
            case 'updateRoleLevel':
                return [
                    'level' => [
                        'required',
                        'integer',
                        'between:1,5'
                    ]
                ];
            case 'updateRolePermission':
                return [
                    'role_guid' => 'required|exists:role_has_permissions,role_guid',
                    'permission_guid' => 'required|exists:role_has_permissions,permission_guid',
                    'action_scope' => 'required|integer|between:1,6',
                    'operation_number' => 'required|integer|max:9999',
                    'follow_up' => 'required|integer|between:1,3'
                ];
            default:
                {
                    return [];
                }
        }
    }
}
