<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RoleHasPermission;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends Model
{
    protected $user;

    public function __construct()
    {
       $this->user = Common::user();
    }

    //获取该登录人所属公司角色列表
    public function getList($request)
    {
        return Role::where(['company_guid' => $this->user->company_guid])->paginate($request->per_page??10);
    }

    //添加角色
    public function addRole($request)
    {
        //获取全部权限
        $permissionId = Permission::all()->pluck('guid')->toArray();

        \DB::beginTransaction();
        try {
            $role = Role::create([
               'guid' => Common::getUuid(),
                'company_guid' =>  $this->user->company_guid,
                'name' => $request->name,
                'level' => $request->level
            ]);

            foreach ($permissionId as $v) {
                 RoleHasPermission::create([
                    'guid' => Common::getUuid(),
                    'role_guid' => $role->guid,
                    'permission_guid' => $v,
                    'action_scope' => $role->level,
                    'operation_number' => 30,
                    'follow_up' => 1
                ]);
            }

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('角色添加失败'.$exception->getMessage());
            return false;
        }

    }

    //修改角色名称
    public function updateRoleName($request)
    {
        return Role::where('guid',$request->guid)->update(['name' => $request->name]);

    }

    //修改角色级别
    public function updateRoleLevel($request)
    {
       return Role::where('guid',$request->guid)->update(['level' => $request->level]);
    }

    //删除角色及相关权限关联
    public function delRole($role)
    {
        \DB::beginTransaction();
        try {
            $role->delete();
            RoleHasPermission::where('role_guid', $role->guid)->delete();
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('删除失败'.$exception->getMessage());
            return false;
        }
    }
    
}