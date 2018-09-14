<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Controllers\Traits\QiNiu;
use App\Http\Requests\Admin\PlatformsRequest;
use App\Repositories\PlatformsRepository;
use App\Services\PlatformsService;
use Illuminate\Http\Request;

class PlatformsController extends APIBaseController
{
    use QiNiu;
    
    // 平台房源列表
    public function index
    (
        PlatformsService $service,
        PlatformsRequest $request
    )
    {
        $res = $service->getList($request);
        if (!$res) $this->sendError('平台房源列表获取失败');
        return $this->sendResponse($res, '平台房源列表获取成功');
    }

    public function show
    (
        $guid,
        PlatformsService $service
    )
    {
        $res = $service->getInfo($guid);
        if (!$res) return $this->sendError('平台房源详情获取失败');
        return $this->sendResponse($res,'平台房源详情获取成功');
    }

    // 添加平台房源
    public function store
    (
        PlatformsRequest $request,
        PlatformsRepository $repository
    )
    {
        $res = $repository->addHouse($request);
        if (!$res) return $this->sendError('平台新增房源失败');
        return $this->sendResponse($res,'平台新增房源成功');
    }


    // 所有的楼座下拉数据
    public function buildingBlocksSelect()
    {
        $res = curl(config('hosts.building').'/api/building_blocks_all','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }
}
