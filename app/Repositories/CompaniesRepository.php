<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class CompaniesRepository extends Model
{
    // 公司列表
    public function getList($request)
    {
        $data = [];
        $res = Company::with('user', 'city:guid,name')->paginate($request->per_page??10);
        foreach ($res as $key => $v) {
            $data[$key]['company_name'] = $v->name;
    }
    dd($data);
        return $res;

    }
    // 添加公司信息
    public function addCompany($request)
    {
        \DB::beginTransaction();
        try {
            $company = Company::create([
                'guid' => Common::getUuid(),
                'name' => $request->name,
                'city_guid' => $request->city_guid,
                'area_guid' => $request->area_guid,
                'address' => $request->address,
                'company_tel' => $request->company_tel
            ]);
            if (empty($company)) throw new \Exception('公司添加失败');

            $user = User::create([
                'guid' => Common::getUuid(),
                'tel' => $request->tel,
                'name' => $request->username,
                'remarks' => $request->remarks,
                'password' => bcrypt($request->tel),
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

    // 修改公司信息
    public function updateCompany($request,$company)
    {
        \DB::beginTransaction();
        try {
            $company->name = $request->name;
            $company->city_guid = $request->city_guid;
            $company->area_guid = $request->area_guid;
            $company->address = $request->address;
            $company->company_tel = $request->company_tel;

            if (!$company->save()) throw new \Exception('公司信息修改失败');

            $users = User::where('company_guid',$company->guid)->first();
            if (!empty($users)) {
                $users->tel = $request->tel;
                $users->name = $request->username;
                $users->remarks = $request->remarks;
                $users->password = bcrypt($request->tel);

                if (!$users->save()) throw new \Exception('用户信息修改失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }
}