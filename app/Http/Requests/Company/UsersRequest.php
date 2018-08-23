<?php

namespace App\Http\Requests\Company;

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
                    'tel.unique' => '手机号不能重复',
                    'role_guid.in' => '角色必须存在',
                    'rel_guid.in' => '门店必须存在',
                ];
            case 'update':
                return [
                    'tel.unique' => '手机号不能重复',
                    'role_guid.in' => '角色必须存在',
                    'rel_guid.in' => '门店必须存在',
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
                    'tel' => 'required|max:16|unique:users,tel',
                    'status' => [
                        'integer',
                        'between:1,3',
                    ],
                    'sex' => [
                        'required',
                        'integer',
                        'between:1,2',
                    ],
                    'entry' => 'required',
                    'birth' => 'required',
                    'native_place' => 'required|max:32',
                    'race' => 'required|max:16',
                ];
            case 'update':
                return [
                    'name' => 'required|max:64',
                    'tel' => [
                        'required',
                        'max:16',
                        Rule::unique('users')->ignore($this->route('user')->guid,'guid'),
                    ],
                    'sex' => [
                        'required',
                        'integer',
                        'between:1,2',
                    ],
                    'entry' => 'required',
                    'birth' => 'required',
                    'native_place' => 'required|max:32',
                    'race' => 'required|max:16',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
