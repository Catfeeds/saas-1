<?php

namespace App\Http\Requests\Company;

use App\Handler\Common;
use App\Models\CompanyFramework;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompanyFrameworksRequest extends FormRequest
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

    // 中文报错
    public function messages()
    {
        switch ($this->route()->getActionMethod()) {
            case 'addArea':
                return [
                    'storefront_guid.*.in' => '请添加存在的门店'
                ];
            case 'addStorefront':
                return [
                    'parent_guid.in' => '请添加存在的区域',
                    'userGuid.*.in' => '用户必须存在'
                ];
            case 'addGroup':
                return [
                    'parent_guid.in' => '请添加存在的门店',
                    'userGuid.*.in' => '用户必须存在'
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
        // TODO 公司guid 已修改

        switch ($this->route()->getActionMethod()) {
            case 'addArea':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ],
                    'storefront_guid' => 'nullable|array',
                    'storefront_guid.*' => [
                        Rule::in(
                            CompanyFramework::where(['level' => 2, 'company_guid' => Common::user()->company_guid])->pluck('guid')
                                ->toArray()
                        )
                    ]
                ];
            case 'addStorefront':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ],
                    'userGuid' => 'nullable|array',
                    'userGuid.*' => [
                        Rule::in(
                            User::where(['company_guid' => Common::user()->company_guid ])->pluck('guid')->toArray()
                        )
                    ],
                    'parent_guid' => [
                        'nullable',
                        Rule::in(
                            CompanyFramework::where(['level' => 1, 'company_guid' => Common::user()->company_guid])->pluck('guid')
                                ->toArray()
                        )
                    ]
                ];
            case 'addGroup':
                return [
                    'name' => [
                        'required',
                        'max:32',
                    ],
                    'parent_guid' => [
                        'required',
                        Rule::in(
                            CompanyFramework::where(['level' => 2, 'company_guid' => Common::user()->company_guid])->pluck('guid')
                                ->toArray()
                        )
                    ],
                    'userGuid' => 'nullable|array',
                    'userGuid.*' => [
                        Rule::in(
                            User::where(['company_guid' => Common::user()->company_guid])->pluck('guid')->toArray()
                        )
                    ],
                ];
            default;
                return [

                ];
        }
    }
}
