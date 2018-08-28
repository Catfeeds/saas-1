<?php

namespace App\Http\Requests\Company;

use App\Models\Track;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TracksRequest extends FormRequest
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
                    'rel_guid' => [
                        'max:32',
                        Rule::in(
                            Track::where('model_type',$this->model_type)->pluck('rel_guid')->toArray()
                        )
                    ],
                    'tracks_info' => 'required|max:255'
                ];
            default:
                {
                    return [];
                }
        }
    }
}
