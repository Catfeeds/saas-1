<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\HousesRequest;
use App\Models\House;
use App\Repositories\HousesRepository;

class HousesController extends APIBaseController
{
    // 添加房源
    public function store
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->addHouse($request);
        return $this->sendResponse($res,'添加房源成功');
    }


    public function edit($id)
    {
        //
    }

    // 更新房源信息
    public function update
    (
        HousesRequest $request,
        House $house,
        HousesRepository $repository
    )
    {
        $res = $repository->updateHouse($house,$request);
        return $this->sendResponse($res,'更新房源成功');
    }

    public function destroy($id)
    {
        //
    }

    // 获取所有下拉数据
    public function getAllSelect()
    {
        $res = curl(config('hosts.building').'/api/get_all_select','GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 修改房源图片
    public function updateImg
    (
        $guid,
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->updateImg($guid,$request);
        if (!$res) return $this->sendError('修改房源图片失败');
        return $this->sendResponse($res,'修改房源图片成功');
    }

}
