<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Redis\MasterRedis;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class APIBaseController extends Controller
{
    // 发送成功请求
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message
        ];
        return response()->json($response, 200);
    }

    // 发送失败请求
    public function sendError($errorMessages = '', $code = 415)
    {
        $response = [
            'success' => false,
            'message' => $errorMessages,
        ];
        return response()->json($response, $code);
    }

    //发送短信验证码
    public function  sendCode($tel, $temp)
    {
        // 生成6位随机验证码
        $code = mt_rand(100000, 999999);
        switch ($temp) {
            case 'updatePwd':
                $template = config('sms.clw.updatePwd');
                $smsTemplate = sprintf($template, $code, config('setting.sms_life_time') / 60);
                break;
            case 'register':
                $template = config('sms.clw.register');
                $smsTemplate = sprintf($template, $code, config('setting.sms_life_time') / 60);
                break;
                default;
                break;
        }

        $smsService = new SmsService();
        if (config('sms.open')) {
            $smsRes = $smsService->sendSMS($tel, $smsTemplate);
            if ($smsRes['status'] != true) return $this->sendError($smsRes['message']);
        } else {
            Log::debug('短信发送配置关闭，发送给：' . $tel . ' 内容：' . $smsTemplate);
        }
        $masterRedis = new MasterRedis();
        // 写入redis,并且设置有效期
        $key = config('redisKey.STRING_SMSCODE_') . $temp . ':' . $tel;
        $masterRedis->addString($key, $code, config('setting.sms_life_time'));
        return $this->sendResponse(true, '验证码发送成功');
    }
}
