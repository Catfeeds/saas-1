<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\TracksRequest;
use App\Repositories\TracksRepository;

class TracksController extends APIBaseController
{
    // 写跟进
    public function store
    (
        TracksRequest $request,
        TracksRepository $repository
    )
    {
        $res = $repository->addTrack($request);
        if (!$res) return $this->sendError('写跟进失败');
        return $this->sendResponse($res,'写跟进成功');
    }
}
