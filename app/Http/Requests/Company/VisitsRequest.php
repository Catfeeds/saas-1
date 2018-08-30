<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitsRequest extends FormRequest
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
                    'rel_guid.in' => '房源/客源必须存在',
                    'accompany.exists' => '陪看人员必须存在'
                ];
                default;
                return [];
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if (empty($this->model_type)) {
            $array = [];
        } else {
            $array = $this->model_type::all()->pluck('guid')->toArray();
        }

       switch ($this->route()->getActionMethod()) {
           case 'store':
               return [
                   'accompany' => 'nullable|exists:users,guid',
                   'rel_guid' => [
                       'required',
                       Rule::in($array)
                   ],
                   'remarks' => 'required|max:255',
                   'visit_img' => 'nullable',
                   'visit_date' => 'required',
                   'visit_time' => 'required'
               ];
               default;
               return [

               ];

       }
    }
}
