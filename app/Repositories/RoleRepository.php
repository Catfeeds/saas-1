<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleRepository extends Model
{
    //添加角色
    public function addRole($request)
    {
        return Role::create([
            'guid' => Common::getUuid(),
            'name' => $request->name,
            'level' => $request->level,
        ]);
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
    
}