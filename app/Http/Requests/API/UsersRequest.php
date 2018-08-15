<?php

namespace App\Http\Requests\API;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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

    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'store':
                return [
                    'tel.unique' => '手机号不能重复',
                    'role_id.in' => '角色必须存在'
                ];
            case 'update':
                return [
                    'tel.unique' => '手机号不能重复',
                    'role_id.in' => '角色必须存在'
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
            case 'store';
                return [
                    'tel' => 'required|max:16|unique:users,tel',
                    'name' => 'required|max:64',
                    'role_id' => [
                        'required',
//                        Rule::in(
//                            Role::where('')
//                        )
                    ],
                ];
            case 'update':
                return [
                    'tel' => 'required|max:16|unique:users,tel',
                    'name' => 'required|max:64',
                    'role_id' => [
                        'nullable',
//                        Rule::in(
//                            Role::where('')
//                        )
                    ],
                ];
            default:
                {
                    return [];
                }
        }
    }
}
