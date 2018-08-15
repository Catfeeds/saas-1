<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class RelUserRequest extends FormRequest
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
                    'rel_guid' => 'required',
                    'model_type' => 'required|max:32',
                ];
            case 'update':
                return [
                    'rel_guid' => 'required',
                    'model_type' => 'required|max:32',
                ];
            default:
                {
                    return [];
                }
        }
    }
}
