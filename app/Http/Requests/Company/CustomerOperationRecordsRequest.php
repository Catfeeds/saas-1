<?php

namespace App\Http\Requests\Company;

use App\Models\House;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerOperationRecordsRequest extends FormRequest
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
            case 'index':
                return [
                    'customer_guid.exists' => '客源必须存在'
                ];
            default;
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
            case 'index':
                return [
                    'customer_guid' => 'required|max:32|exists:customer_operation_records,customer_guid',
                    'type' => 'nullable|integer|between:1,6'
                ];
                default;
                return [

                ];
        }
    }
}
