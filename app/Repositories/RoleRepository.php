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
    public function updateRoleName($request,$role)
    {
        $role->name = $request->name;
        if(!$role->save()) return false;
        return true;
    }

    //修改角色级别
    public function updateRloeLevel($request,$role)
    {
        $role->level = $request->level;
        if(!$role->save())  return false;
        return true;
    }
    
}