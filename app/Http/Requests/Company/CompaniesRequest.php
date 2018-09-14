<?php

namespace App\Http\Requests\Company;

use App\Models\Company;
use App\Models\User;
use function GuzzleHttp\default_ca_bundle;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompaniesRequest extends FormRequest
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
                    'name.unique' => '公司名称不能重复添加',
                    'contacts_tel.not_in' => '联系电话不能重复',
                ];
            case 'update':
                return [
                    'name.not_in' => '公司名称不能重复添加',
                    'contacts_tel.not_in' => '用户电话不能重复',
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
            case 'store':
                return [
                    'name' => 'required|max:128|unique:companies',
                    'address' => 'required|max:256',
                    'city_guid' => 'required|max:32',
                    'area_guid' => 'required|max:32',
                    'company_tel' => 'required|max:16',
                    'contacts_tel' => [
                        'required',
                        'max:16',
                        Rule::notIn(
                            User::all()->pluck('tel')->toArray()
                        )
                    ],
                    'contacts' => 'required|max:64',
                    'job_remarks' => 'required|max:32',
                ];
            case 'update':
                return [
                    'name' => [
                        'required',
                        'max:128',
                        Rule::unique('companies')->ignore($this->route('company')->guid,'guid')
                    ],
                    'address' => 'nullable|max:256',
                    'city_guid' => 'required|max:32',
                    'area_guid' => 'required|max:32',
                    'company_tel' => 'required|max:16',
                    'contacts_tel' => [
                        'required',
                        'max:16',
                        Rule::unique('companies')->ignore($this->route('company')->guid,'guid')
                    ],
                    'job_remarks' => 'required|max:32',
                    'contacts' => 'required|max:64',
                ];
            default;
                return [

                ];
        }
    }
}
