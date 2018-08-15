<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class UserInfoRequest extends FormRequest
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
                   'sex' => 'required',
                   'entry' => 'required',
                   'birth' => 'required',
                   'native_place' => 'required',
                   'race' => 'required',
               ];
           case 'update':
               return [
                   'sex' => 'required',
                   'entry' => 'required',
                   'birth' => 'required',
                   'native_place' => 'required',
                   'race' => 'required',
               ];
       }
    }
}
