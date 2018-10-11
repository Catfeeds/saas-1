<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\UsersRequest;
use App\Models\Customer;
use App\Models\House;
use App\Models\User;
use App\Services\LoginsService;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersController extends APIBaseController
{
    // 添加用户
    public function store
    (
        UsersRequest $request,
        UserService $service
    )
    {
        $res = $service->addUser($request);
        if ($res) return $this->sendResponse($res,'添加用户成功');
        return $this->sendError($res,'添加用户失败');
    }

    //员工修改之前原始数据
    public function edit(User $user)
    {
        $data = [];
        if ($user->companyFramework) {
            switch ($user->companyFramework->level) {
                case 1:
                    $data[] = $user->rel_guid;
                    break;
                case 2:
                    if ($user->companyFramework->upper) {
                        $data[] = $user->companyFramework->upper->guid;
                    }
                    $data[] = $user->rel_guid;
                    break;
                case 3:
                    dd($user->companyFramework->upper->upper);
                    if ($user->companyFramework->upper->upper) {
                        $data[] = $user->companyFramework->upper->upper->guid;
                    }
                    $data[] = $user->companyFramework->upper->guid;
                    $data[] = $user->rel_guid;
                    break;
                default;
                    break;
            }
        }
        $user->conpamyFramework = $data;
        $user->sex = optional($user->detailInfo)->sex;
        return $this->sendResponse($user, '员工修改之前原始数据');
    }

    // 修改用户
    public function update
    (
        UsersRequest $request,
        User $user,
        UserService $service
    )
    {
        $res = $service->updateUser($request,$user);
        if (empty($res)) return $this->sendError($res,'修改用户失败');
        return $this->sendResponse($res,'修改用户修改成功');
    }

    // 删除用户
    public function destroy
    (
        User $user,
        UserService $service
    )
    {
        // 验证该用户是否还有资源
//        $validate = $this->getUserHouseCustomer($user->guid);
//        if ($validate) return $this->sendError('请转移该用户手上的资源在进行离职操作');
        $res = $service->del($user);
        return $this->sendResponse($res,'删除用户成功');
    }

    // 冻结用户
    public function freeze
    (
        UsersRequest $request,
        UserService $service
    )
    {
        $res = $service->freeze($request);
        return $this->sendResponse($res,'冻结成功');
    }

    // 人员离职
    public function resignation
    (
        UsersRequest $request,
        UserService $service
    )
    {
        // 验证该用户是否还有资源
//        $validate = $this->getUserHouseCustomer($request);
//        if ($validate) return $this->sendError('请转移该用户手上的资源在进行离职操作');
        $res = $service->resignation($request);
        return $this->sendResponse($res, '离职成功');
    }

    // 微信确认
    public function confirmWechat
    (
        UsersRequest $request,
        LoginsService $service
    )
    {
        $tel = $service->getTel($request->saftySign);
        // 查库
        $openid = User::where('tel', $tel)->value('openid');
        // 比较openid
        if ($openid === $request->openid) {
            // 验证成功,则返回换绑二维码
            $key = $service->cipher($request->getClientIp(), $tel);
            $res = curl(config('setting.wechat_url').'/temporary/'. $key .'/update_wechat','get');
            if (empty($res->data)) return $this->sendError('二维码获取失败');
            return $this->sendResponse($res->data, '二维码获取成功');
        } else {
            return $this->sendError('验证失败');
        }
    }

    // 微信换绑
    public function updateWechat
    (
        UsersRequest $request
    )
    {
        $user = Common::user();

        if(empty($user->guid)){
            return $this->sendError('请先登录');
        }
        $openid = User::where('guid','!=',$user->guid)->where(['openid' => $request->openid])->first();

        if(!empty($openid)) {
            return $this->sendError( '绑定失败当前微信已绑定其他账号');
        }
        $user->update(['openid' => $request->openid]);

        if(empty($user)) {
            return $this->sendError( '绑定失败');
        }
        return $this->sendResponse([], '换绑绑定成功');
    }

    // 获取全部岗位
    public function getAllQuarters(UserService $service)
    {
        $res = $service->getAllQuarters();
        return $this->sendResponse($res, '岗位获取成功');
    }

    // 登录人信息
    public function show()
    {
        $user = Common::user();
        if (empty($user)) return $this->sendError('登录账户异常');

        if ($user->status !== 1 || $user->start_up == 2) return $this->sendError('账户无效');

        $res = $user->toArray();
        $res['company_name'] = $user->company->name; // 公司名
        $res['company_guid'] = $user->company->guid; // 公司guid
        $res['role_name'] = $user->role->name;  // 角色名称
        $res['role_level'] = $user->role->level; // 角色等级
        $res['storefront'] = empty($user->companyFramework)?$user->company->name:$user->companyFramework->name;
        //根据当前登录用户角色,获取所有权限
        $res['permission'] = $user->role->permission->pluck('name_en')->toArray()??[];
        return $this->sendResponse($res, '用户信息获取成功');
    }

    // 用户详细信息
    public function getUser(UsersRequest $request)
    {
        $res = User::with('detailInfo')->where('guid', $request->guid)->first();
        return $this->sendResponse($res, '获取成功');
    }

    // 重置密码
    public function resetPwd(
        UsersRequest $request,
        UserService $service
    )
    {
        $res = $service->resetPwd($request);
        return $this->sendResponse($res,'重置密码成功');
    }

    // 发送短信验证
    public function sendMessage($tel, $temp)
    {
        return  $this->sendCode($tel, $temp);
    }

    // 修改密码
    public function updatePwd
    (
        UsersRequest $request
    )
    {
//        $res = $service->updatePwd($request);
//        if (!$res['status']) return $this->sendError($res['message']);
//        return $this->sendResponse(true, $res['message']);
        // 修改当前登录人密码
        $user = Common::user();
        // 判断新旧密码是否一致
        if (!Hash::check($request->old, $user->password)) return $this->sendError('原始密码不正确');
        $user->password = bcrypt($request->new);
        if (!$user->save()) return $this->sendError('修改失败');
        return $this->sendResponse(true,'修改成功');
//        $res = $service->updatePwd($request);
//        return $this->sendResponse($res, '修改成功');
    }
    
    // 获取公司下所有人员
    public function getAllUser(
        UserService $service,
        UsersRequest $request
    )
    {
        $res = $service->getAllUser($request);
        return $this->sendResponse($res->map(function($v) {
            return [
                'value' => $v->guid,
                'label' => $v->name,
                'tel' => '电话:' . $v->tel,
                'pic' => empty($v->pic)?config('setting.user_default_img'):config('setting.qiniu_url').$v->pic,
                'storefront' => empty($v->companyFramework)?'':$v->companyFramework->name
            ];
        }),'获取公司下所有人员成功');
    }

    // 获取用户房源,客源资源数据
    public function getUserHouseCustomer(
        $guid
    )
    {
        // 查询房源
        $house = House::where([
            'guardian_person' => $guid,
            'status' => 1
        ])->get();
        if (!$house->isEmpty()) return false;

        // 查询客户
        $customer = Customer::where([
            'guardian_person' => $guid,
            'status' => 1
        ])->get();
        if (!$customer->isEmpty()) return false;

        return true;
    }

    // 更换头像
    public function updatePic
    (
        UserService $service,
        UsersRequest $request
    )
    {
        $user = Common::user();
        $res = $service->updatePic($user, $request);
        if (!$res) return $this->sendError('修改失败');
        return $this->sendResponse($res, '修改成功');
    }
}
