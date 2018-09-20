<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\FeedBacksReauest;
use App\Repositories\FeedBacksRepository;

class FeedBacksController extends APIBaseController
{
    // 添加问题反馈
    public function store
    (
        FeedBacksReauest $request,
        FeedBacksRepository $repository
    )
    {
        $res = $repository->addFeedBack($request);
        if (!$res) return $this->sendError('问题反馈添加失败');
        return $this->sendResponse($res,'问题反馈添加成功');
    }
}
