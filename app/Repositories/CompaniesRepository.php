<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompaniesRepository extends Model
{
    // 添加公司
    public function addCompany($request)
    {
        \DB::beginTransaction();
        try {
            $company = Company::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'slogan' => $request->slogan,
                'license' => $request->license,
                'address' => $request->address,
            ]);
            if (empty($company)) throw new \Exception('公司添加失败');
            $user = User::create([
                'guid' => Common::getUuid(),
                'tel' => $request->tel,
                'password' => bcrypt($request->password),
                'company_guid' => $company->guid,
            ]);
            if (empty($user)) throw new \Exception('用户信息同步失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
}