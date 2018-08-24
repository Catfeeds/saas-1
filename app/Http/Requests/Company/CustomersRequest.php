<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;

class CustomersRequest extends FormRequest
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
        return [

        ];
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
                    'level' =>'nullable|integer|between:1,3',
                    'guest' => 'required|integer|between:1,3',
                    'customer_info' => 'required',
                    'remarks' => 'nullable',
                    'intention' => 'nullable',
                    'block' => 'nullable',
                    'building' => 'nullable',
                    'house_type' => 'nullable',
                    'price' => 'nullable',
                    'acreage' => 'nullable',
                    'type' => 'nullable|integer',
                    'renovation' => 'nullable|integer',
                    'floor' => 'nullable',
                    'target' => 'nullable'
                ];
                break;
            case 'update':
                return [
                    'level' =>'nullable|integer|between:1,3',
                    'guest' => 'required|integer|between:1,3',
                    'customer_info' => 'required',
                    'remarks' => 'nullable',
                    'intention' => 'nullable',
                    'block' => 'nullable',
                    'building' => 'nullable',
                    'house_type' => 'nullable',
                    'price' => 'nullable',
                    'acreage' => 'nullable',
                    'type' => 'nullable|integer',
                    'renovation' => 'nullable|integer',
                    'floor' => 'nullable',
                    'target' => 'nullable'
                ];
                default;
                return[
                ];
        }
    }
}
