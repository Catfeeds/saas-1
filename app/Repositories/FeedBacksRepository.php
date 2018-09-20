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
            $feedback = FeedBack::create([
                'guid' => Common::getUuid(),
                'user_guid' => Common::user()->guid,
                'content' => $request->content,
            ]);
            $company = Company::where('guid',Common::user()->company_guid)->first();
            // 拼接发送内容
            $message = $feedback->content . ' @反馈人:' . Common::user()->name . ' ,所属公司:' . $company->name . ' ,联系方式:' . Common::user()->tel;
            return $message;
    }
}