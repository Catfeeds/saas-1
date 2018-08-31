<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\TracksRequest;
use App\Models\Track;
use App\Services\TracksService;

class TracksController extends APIBaseController
{
    // 写跟进
    public function store
    (
        TracksRequest $request,
        TracksService $service
    )
    {
        $res = $service->addTrack($request);
        if (!$res) return $this->sendError('跟进添加失败');
        return $this->sendResponse($res,'写跟进成功');
    }

    // 修改跟进信息
    public function update
    (
        TracksRequest $request,
        Track $track,
        TracksService $service
    )
    {
        $res = $service->updateTrack($request, $track);
        if (!$res) return $this->sendError('修改跟进信息失败');
        return $this->sendResponse($res,'修改跟进信息成功');
    }
}
