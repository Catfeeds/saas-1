<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\RemindsRequest;
use App\Repositories\RemindsRepository;

class RemindsController extends APIBaseController
{
    // 写提醒
    public function store
    (
        RemindsRequest $request,
        RemindsRepository $repository
    )
    {
        $res = $repository->writeReminder($request);
        if (!$res) return $this->sendError('写提醒失败');
        return $this->sendResponse($res,'写提醒成功');
    }
}
