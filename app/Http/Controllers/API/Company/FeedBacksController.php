<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\FeedBacksReauest;
use App\Repositories\FeedBacksRepository;
use App\Services\DingTalkService;


class FeedBacksController extends APIBaseController
{
    // 添加问题反馈
    public function store
    (
        FeedBacksReauest $request,
        FeedBacksRepository $repository,
        DingTalkService $service
    )
    {
        $message = $repository->addFeedBack($request);
        $data = $service->sendMessages($message);
        return $this->sendResponse($data,'问题反馈添加成功');
    }
}
