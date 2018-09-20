<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\BaseModel;
use App\Models\Company;
use App\Models\FeedBack;

class FeedBacksRepository extends BaseModel
{
    // 添加问题反馈
    public function addFeedBack($request)
    {
        \DB::beginTransaction();
        try {
            $feedback = FeedBack::create([
                'guid' => Common::getUuid(),
                'user_guid' => Common::user()->guid,
                'content' => $request->content,
            ]);
            if (empty($feedback)) throw new \Exception('添加问题反馈失败');

            $company = Company::where('guid',Common::user()->company_guid)->first();
            $webhook = config('hosts.webhook');
            $message = $feedback->content . ' @反馈人:' . Common::user()->name . ' ,所属公司:' . $company->name . ' ,联系方式:' . Common::user()->tel;
            $data = array ('msgtype' => 'text','text' => array ('content' => $message));
            $data_string = json_encode($data);
            $result = $this->request_by_curl($webhook, $data_string);
            if (empty($result)) throw new \Exception('发送问题反馈失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }


    }

    public function request_by_curl($remote_server, $post_string) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array ('Content-Type: application/json;charset=utf-8'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
        // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}