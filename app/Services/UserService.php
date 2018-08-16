<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\RelUser;
use App\Models\User;
use App\Models\UserInfo;

class UserService
{
    //添加用户
    public function addUser($request)
    {
        \DB::beginTransaction();
        try {
            $user = User::create([
                'guid' => Common::getUuid(),
                'tel' => $request->tel,
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'role_id' => $request->role_id,
            ]);
            if (empty($user)) throw new \Exception('用户添加失败');

            $rel_user = RelUser::create([
                'guid' => Common::getUuid(),
                'user_guid' => $user->guid,
                'rel_guid' => $request->rel_guid,
                'model_type' => $request->model_type
            ]);
            if (empty($rel_user)) throw new \Exception('片区,组与门店关联表插入数据失败');

            $user_info = UserInfo::create([
                'guid' => Common::getUuid(),
                'user_guid' => $user->guid,
                'sex' => $request->sex,
                'entry' => $request->entry,
                'birth' => $request->birth,
                'native_place' => $request->native_place,
                'race' => $request->race
            ]);
            if (empty($user_info)) throw new \Exception('用户基础信息添加失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    //修改用户信息
    public function updateUser($request, $user)
    {
        \DB::beginTransaction();
        try {
            $user->tel = $request->tel;
            $user->name = $request->name;
            $user->password = bcrypt($request->password);
            $user->role_id = $request->role_id;
            if (!$user->save()) throw new \Exception('用户修改失败');

            $rel_user = RelUser::where('user_guid',$user->guid)->first();
            if (!empty($rel_user)) {
                $rel_user->user_guid = $user->guid;
                $rel_user->rel_guid = $request->rel_guid;
                $rel_user->model_type = $request->model_type;
                if (!$rel_user->save()) throw new \Exception('片区,组与门店关联表修改数据失败');
            }

            $user_info = UserInfo::where('user_guid',$user->guid)->first();
            if (!empty($user_info)) {
                $user_info->user_guid = $user->guid;
                $user_info->sex = $request->sex;
                $user_info->entry = $request->entry;
                $user_info->birth = $request->birth;
                $user_info->native_place = $request->native_place;
                $user_info->race = $request->race;
                if (!$user_info->save()) throw new \Exception('用户基础信息修改失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
    
    //删除用户
    public function del($user)
    {
        \DB::beginTransaction();
        try {
            //删除员工表
            $res = $user->delete();
            if (!$res) throw new \Exception('用户删除失败');
            //删除片区,组与门店关联表数据
            $suc = RelUser::where('user_guid',$user->guid)->delete();
            if (!$suc) throw new \Exception('片区组与门店关联表数据删除失败');
            //删除用户基础信息
            $succ = UserInfo::where('user_guid',$user->guid)->delete();
            if (!$succ) throw new \Exception('用户基础信息删除失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    //冻结用户
    public function freeze($guid)
    {
        return User::where(['guid' => $guid])->update(['status' => 3]);
    }
    
    //人员离职
    public function resignation($guid)
    {
        return User::where(['guid' => $guid])->update(['status' => 2]);
    }
}