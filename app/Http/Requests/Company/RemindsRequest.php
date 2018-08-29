<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RemindsRequest extends FormRequest
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
                    'rel_guid.in' => '房源/客源必须存在'
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
                   'remind_info' => 'required|max:255',
                   'rel_guid' => [
                       'required',
                       'max:32',
                       Rule::in(
                           $this->model_type::all()->pluck('guid')->toArray()
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
