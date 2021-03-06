<?php

namespace App\Http\Requests\Company;

use App\Handler\Common;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UsersRequest extends FormRequest
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

    //验证错误消息
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'tel.unique' => '该人员已存在并且在职',
                    'role_guid.in' => '角色必须存在',
                    'rel_guid.in' => '门店必须存在',
                ];
            case 'update':
                return [
                    'tel.unique' => '手机号已存在',
                    'role_guid.in' => '角色必须存在',
                    'rel_guid.in' => '门店必须存在',
                ];
            case 'resetPwd':
                return [
                    'tel.in' => '用户必须存在',
                ];
            case 'freeze':
            case 'resignation':
                return [
                    'guid.in' => '用户必须存在',
                ];
            default:
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
            case 'store':
                return [
                    'name' => 'required|max:64',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::unique('users', 'tel')->where(function($q) {
                            $q->where('status', 1);
                        })
                    ],
                    'role_guid' => [
                        'required',
                        Rule::in(
                            Role::where(['company_guid'=> Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ],
                    'status' => [
                        'integer',
                        'between:1,3',
                    ],
                    'sex' => [
                        'nullable',
                        'integer',
                        'between:1,2',
                    ],
                    'entry' => 'nullable',
                    'birth' => 'nullable',
                    'native_place' => 'nullable|max:32',
                    'race' => 'nullable|max:16',
                ];
            case 'update':
                return [
                    'name' => 'required|max:64',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::unique('users')->where(function ($q) {
                            $q->where('status', 1);
                        })->ignore($this->route('user')->guid,'guid'),
                    ],
                    'role_guid' => [
                        'required',
                        Rule::in(
                            Role::where(['company_guid'=> Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ],
                    'sex' => [
                        'nullable',
                        'integer',
                        'between:1,2',
                    ],
                    'entry' => 'nullable',
                    'birth' => 'nullable',
                    'native_place' => 'nullable|max:32',
                    'race' => 'nullable|max:16',
                ];
            case 'resetPwd':
                return [
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::in(
                            User::all()->pluck('tel')->toArray()
                        )
                    ]
                ];
            case 'updatePwd':
                return [
                    'new' => 'min:6'
                ];
            case 'resignation':
                return [
                    'guid' => [
                        'required',
                        'max:64',
                        Rule::in(
                            User::all()->pluck('guid')->toArray()
                        )
                    ]
                ];
            default:
                {
                    return [];
                }
        }
    }
}
