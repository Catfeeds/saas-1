<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Company;
use App\Models\Role;
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
            $data[$key]['guid'] = $v->guid;
            $data[$key]['status'] = $v->status;
            $data[$key]['company_name'] = $v->name;
            $data[$key]['city'] = $v->city['name'];
            $data[$key]['address'] = $v->address;
            $user = $v->user->where('created_at',$v->created_at)->toArray();
            $data[$key]['name'] = $user[0]['name'];
            $data[$key]['tel'] = $user[0]['tel'];
        }

        return $res->setCollection(collect($data));

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

            $role = Role::create([
                'guid' => Common::getUuid(),
                'company_guid' => $company->guid,
                'name' => '管理员',
                'level' => 1,
            ]);
            if (empty($role)) throw new \Exception('添加角色失败');

            $user = User::create([
                'guid' => Common::getUuid(),
                'role_guid' => $role->guid,
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

    // 启用状态
    public function enabledState($request)
    {
        // 启用
        if ($request->status == 1) {
            return Company::where('guid',$request->guid)->update(['status' => $request->status]);
        } elseif ($request->status == 2) {
            // 禁用
            return Company::where('guid',$request->guid)->update(['status' => $request->status]);
        }
    }
}