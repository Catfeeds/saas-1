<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Role;
use App\Models\User;
use App\Models\UserInfo;
use App\Redis\MasterRedis;

class UserService
{
    // 添加用户
    public function addUser($request)
    {
        \DB::beginTransaction();
        try {
            $user = User::create([
                'guid' => Common::getUuid(),
                'tel' => $request->tel,
                'name' => $request->name,
                'password' => bcrypt($request->tel),    // 账号密码默认一样
                'role_guid' => $request->role_guid,
                'rel_guid' => $request->rel_guid,
                'status' => $request->status,
                'company_guid' => Common::user()->company_guid
            ]);
            if (empty($user)) throw new \Exception('用户添加失败');

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

    // 修改用户信息
    public function updateUser($request, $user)
    {
        \DB::beginTransaction();
        try {
            $user->tel = $request->tel;
            $user->name = $request->name;
            $user->role_guid = $request->role_guid;
            $user->rel_guid = $request->rel_guid;
            if (!$user->save()) throw new \Exception('用户修改失败');

            $user_info = UserInfo::where('user_guid',$user->guid)->first();
            if (!empty($user_info)) {
                $user_info->user_guid = $user->guid;
                $user_info->sex = $request->sex;
                $user_info->entry = $request->entry;
                $user_info->birth = $request->birth;
                $user_info->native_place = $request->native_place;
                $user_info->race = $request->race;
                if (!$user_info->save()) throw new \Exception('用户基础信息修改失败');
            } else {
                $update_user_info = UserInfo::create([
                    'guid' => Common::getUuid(),
                    'user_guid' => $user->guid,
                    'sex' => $request->sex,
                    'entry' => $request->entry,
                    'birth' => $request->birth,
                    'native_place' => $request->native_place,
                    'race' => $request->race
                ]);
                if (empty($update_user_info)) throw new \Exception('用户基础信息修改失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }
    
    // 删除用户
    public function del($user)
    {
        \DB::beginTransaction();
        try {
            // 删除员工表
            $res = $user->delete();
            if (!$res) throw new \Exception('用户删除失败');

            // 删除用户基础信息
            $delUserInfo = UserInfo::where('user_guid', $user->guid)->delete();
            if (!$delUserInfo) throw new \Exception('用户基础信息删除失败');

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollBack();
            return false;
        }
    }

    // 冻结用户
    public function freeze($request)
    {
        return User::where(['guid' => $request->guid])->update(['status' => $request->status]);
    }
    
    // 人员离职
    public function resignation($request)

    {
        return User::where(['guid' => $request->guid])->update(['status' => $request->status]);
    }

    // 获取公司下的全部岗位
    public function getAllQuarters()
    {
        $res = Role::where('company_guid', Common::user()->company_guid)
                    ->orderBy('level', 'asc')
                    ->orderBy('created_at','asc')
                    ->get();
        return $res->map(function($v) {
           return [
                'value' => $v->guid,
                'name' => $v->name,
                'level' => $v->level
           ] ;
        });
    }

    // 重置密码
    public function resetPwd($request)
    {
        return User::where(['tel' => $request->tel])->update(['password' => bcrypt($request->tel)]);
    }

    //修改密码
    public function updatePwd($request)
    {
        $masterRedis = new MasterRedis();
        $key = config('redisKey.STRING_SMSCODE_') . 'updatePwd:' . $request->tel;
        // 验证手机短信是否正确
        $telCaptcha = $masterRedis->getString($key, 'old');
        // 判断验证码是否存在
        if (empty($telCaptcha)) return ['status' => false, 'message' => '验证码失效,请重新发送'];
        // 判断验证码是否正确
        if ($request->code != $telCaptcha) return ['status' => false, 'message' => '手机验证码错误，请重新输入'];
        // 验证成功，删除验证码
        $masterRedis->delKey($key);
        $res =  User::where('tel', $request->tel)->update(['password' => bcrypt($request->password)]);
        if (!$res) return ['status' => false, 'message' => '密码修改失败'];
        return ['status' => true, 'message' => '密码修改成功'];
    }

    // 获取公司下所有在职人员
    public function getAllUser($request)
    {
        $userGuid = Access::getUser(Common::user()->role->level);
        $res = User::where(['company_guid' => Common::user()->company_guid, 'status' => 1])->whereIn('guid', $userGuid);
        if (!empty($request->name)) $res = $res->where('name', 'like', '%'. $request->name.'%');
        return $res->get();
    }


}