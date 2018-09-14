<?php

namespace App\Http\Controllers\API\Admin;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Controllers\Traits\QiNiu;
use App\Http\Requests\Admin\HousesRequest;
use App\Repositories\HousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class HousesController extends APIBaseController
{
    use QiNiu;
    // 平台房源列表
    public function index
    (
        HousesRequest $request,
        HousesService $service,
        HousesRepository $repository
    )
    {
        $res = $repository->platformHouseList($request,$service);
        if (!$res) return $this->sendError('平台房源列表获取失败');
        return $this->sendResponse($res,'平台房源列表获取成功');
    }

    public function create()
    {
        //
    }

    // 添加平台房源
    public function store
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->platformAddHouse($request);
        if (!$res) return $this->sendError('平台新增房源失败');
        return $this->sendResponse($res,'平台新增房源成功');
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        //
    }


    public function update(Request $request, $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    // 所有的楼座下拉数据
    public function buildingBlocksSelect()
    {
        $res = curl(config('hosts.building').'/api/building_blocks_all','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }
}
