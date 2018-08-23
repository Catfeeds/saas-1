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

    // 修改公司
    public function updateCompay($request,$company)
    {
        \DB::beginTransaction();
        try {
            $company->name = $request->name;
            $company->slogan = $request->slogan;
            $company->license = $request->license;
            $company->address = $request->address;
            if (!$company->save()) throw new \Exception('公司信息修改失败');
            $user = User::where('company_guid',$company->guid)->first();
            if (!empty($user)) {
                $user->company_guid = $company->guid;
                $user->tel = $request->tel;
                $user->password = bcrypt($request->password);
                if (!$user->save()) throw new \Exception('用户信息修改失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception  $exception) {
            \DB::rollBack();
            return false;
        }
    }
    
    // 删除公司
    public function delCompany($company)
    {
        \DB::beginTransaction();
        try {
            // 删除公司表
            $res = $company->delete();
            if (!$res) throw new \Exception('公司信息删除失败');

            $delUser = User::where('company_guid',$company->guid)->delete();

            if (!$delUser) throw  new \Exception('用户信息删除失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
}