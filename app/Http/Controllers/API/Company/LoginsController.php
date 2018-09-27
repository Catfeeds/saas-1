<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Models\User;
use App\Services\LoginsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Token;

class LoginsController extends APIBaseController
{
    //微信登录查数据库
    public function index(
        Request $request,
        LoginsService $service

    )
    {
        $user = User::where('openid', $request->openid)->first();
        //请求前端登录接口
        if (!empty($user)) curl(config('setting.login_url').'/?openid='.$request->openid.'&saftySign='.$request->saftySign,'get');

    }

    //生成唯一安全标识
    public function create
    (
        Request $request,
        LoginsService $service
    )
    {
        $code =  $request->getClientIp();
        if (!empty($request->tel)) {
            $code .= '-'.$request->tel;
        } else {
            $code .=  '-'.time();
        }
        $key = $service->lock($code);
        return $this->sendResponse($key, '获取成功');
    }

    //手机号、密码登录
    public function store
    (
        Request $request,
        LoginsService $service
    )
    {
        //获取token
        $token = $service->getToken($request->tel, $request->password);
        if (empty($token['success'])) return $this->sendError($token['message']);

        //查询用户是否存在
        $user = User::where(['tel' => $request->tel])->first();

        if (empty($user)) return $this->sendError('用户不存在');
        //判断用户是否有效
        if ($user->status !== 1 || $user->start_up == 2) return $this->sendError('无效账户');
        //查询用户是否绑定微信
        if (empty($user->openid)) {
            if (empty($request->openid)) {
                // node客户端 id
                $key = $request->saftySign;
                //通过密文获取二维码
                $res = curl(config('setting.wechat_url') . '/qrcode/' . $key, 'get');
                if (empty($res->data)) return $this->sendError('二维码获取失败');
                return $this->sendResponse($res->data, '二维码获取成功', 215);
            }
            // 查询用户是否已经绑定
            $openid = User::where(['openid' => $request->openid])->first();
            if (!empty($openid)) return $this->sendError( '改微信号已绑定');
            // 如果参数中存在 openid  给当前账号 加上openid  改
            $res =$user->update(['openid' => $request->openid]);
            if(empty($res)) return  $this->sendError( '绑定失败');
        }

        return $this->sendResponse($token['data'], '获取token成功！', 200);
    }

    // 获取微信登录 二维码
    public function  getWechatLoginCode($code, $status)
    {
        $res = curl(config('setting.wechat_url') . '/temporary/' . $code . '/' . $status, 'get');
        return $this->sendResponse($res->data, '微信登录二维码获取成功', 200);
    }


    //首次登陆绑定微信后跳转登陆
    public function qrscene_
    (
        Request $request,
        LoginsService $service
    )
    {
        $tel = $service->getTel($request->saftySign);
        $res = User::where('tel', $tel)->update(['openid' => $request->openid]);
//        if (!empty($res)) {
//            curl(config('setting.login_url').'/?openid='.$request->openid.'&saftySign='.$request->saftySign,'get');
//        }
        if(empty($res)){
            return  $this->sendError( '绑定失败');
        }
    }

    //微信登录
    public function wechatLogins
    (
        Request $request,
        LoginsService $service
    )
    {
        $data = [
            'saftySign' => null,
            'token' => null,
            'status' => false,
            'message' => '该微信没有绑定'
        ];
        if (empty($request->saftySign)) {
            curl('http://192.168.0.188:3000/wechat/codeLogin','post', $data);
            return;
        }
        // 取消光柱
        $str = preg_replace("/qrscene_/","",$request->saftySign);
        $data['saftySign'] = $str;

        $user = User::where('openid', $request->openid)->first();

        if(empty($user)) {
            curl('http://192.168.0.188:3000/wechat/codeLogin','post', $data);
            return;
        }
        $res = $service->wechatLogins($user);
        if (!$res['status']) {
            $data['message'] = $res['message'];
            curl('http://192.168.0.188:3000/wechat/codeLogin','post', $data);
            return;
        }
        $data['token'] = $res['token'];
        $data['status'] = true;
        curl('http://192.168.0.188:3000/wechat/codeLogin','post', $data);
    }

    //退出登录
    public function logout()
    {
        $user = Auth::guard('api')->user();
        if (empty($user)) {
            return $this->sendError('暂未登录', 403);
        }
        // 获取当前登陆用户的access_token的id
        $accessToken = $user->access_token;
        // 找到这条access_token并且将其删除
        $token = Token::find($accessToken);
        if (empty($token)) return $this->sendError('暂无有效令牌', 403);

        if (!empty($token->delete())) {
            return $this->sendResponse([], '退出成功！');
        } else {
            return $this->sendError('退出失败', 500);
        }
    }

}
