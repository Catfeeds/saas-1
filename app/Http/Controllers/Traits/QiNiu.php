<?php

namespace App\Http\Controllers\Traits;
use App\Handler\Common;

trait QiNiu
{
    // 获取七牛token
    public function token()
    {
        if (empty($token = Common::getToken())) {
            return $this->sendError('获取失败');
        } else {
            return $this->sendResponse($token, '获取token成功!');
        }
    }
}