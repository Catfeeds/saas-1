<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Company;
use App\Models\Role;
use App\Models\User;
use App\Services\QuartersService;
use Illuminate\Database\Eloquent\Model;

class CompaniesRepository extends Model
{
    // 公司列表
    public function getList($request)
    {
        $data = [];
        $res = Company::with('city:guid,name')->paginate($request->per_page??10);
        foreach ($res as $key => $v) {
            $data[$key]['guid'] = $v->guid;
            $data[$key]['status'] = $v->status;
            $data[$key]['company_name'] = $v->name;
            $data[$key]['city'] = $v->city['name'];
            $data[$key]['address'] = $v->address;
            $data[$key]['name'] = $v->contacts;
            $data[$key]['tel'] = $v->contacts_tel;
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
                'company_tel' => $request->company_tel,
                'contacts' => $request->username,
                'contacts_tel' => $request->tel,
                'job_remarks' => $request->remarks
            ]);
            if (empty($company)) throw new \Exception('公司添加失败');

            $role = Role::create([
                'guid' => Common::getUuid(),
                'company_guid' => $company->guid,
                'name' => '管理员',
                'level' => 1,
            ]);
            if (empty($role)) throw new \Exception('添加角色失败');

            // 设置等级
            $request->offsetSet('role_guid', $role->guid);
            $request->offsetSet('level', 1);

            // 添加默认权限
            $quartersService = new QuartersService();
            $res = $quartersService->defaultPermissions($request);
            if (empty($res)) throw new \Exception('岗位级别修改失败');

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
            $company->contacts = $request->username;
            $company->contacts_tel = $request->tel;
            $company->job_remarks = $request->remarks;
            if (!$company->save()) throw new \Exception('公司信息修改失败');

            $users = User::where('tel', $company->contacts_tel)->first();
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

    // 启用
    public function enable($request)
    {
        \DB::beginTransaction();
        try {
            $company = Company::where('guid',$request->guid)->update(['status' => 1]);
            if (empty($company)) throw new \Exception('账户启用失败');

            $user = User::where('company_guid',$request->guid)->update(['status' => 1]);
            if (empty($user)) throw new \Exception('用户状态修改失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }

    }
    
    // 禁用
    public function disable($request)
    {
        \DB::beginTransaction();
        try {
            $company = Company::where('guid',$request->guid)->update(['status' => 2]);
            if (empty($company)) throw new \Exception('账户启用失败');

            $user = User::where('company_guid',$request->guid)->update(['status' => 3]);
            if (empty($user)) throw new \Exception('用户状态修改失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }
}