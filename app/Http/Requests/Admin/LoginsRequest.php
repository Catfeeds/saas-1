<?php

namespace App\Http\Requests\Admin;

use App\Models\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoginsRequest extends FormRequest
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
            case 'logins':
                return [
                    'name' => [
                        'required',
                        'max:32',
                        Rule::in(
                            Admin::all()->pluck('name')->toArray()
                        )
                    ],
                    'password' => 'required|min:6|max:16'
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
            case 'logins':
                return [

                ];
            default:
                {
                    return [];
                }
        }
    }
}
