<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Requests\Company\HousesRequest;
use App\Models\Company;
use App\Models\House;
use App\Repositories\HousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class HousesController extends APIBaseController
{

    // 房源列表
    public function index(
        Request $request,
        HousesRepository $repository
    )
    {
        $res = $repository->houseList($request);
        return $this->sendResponse($res,'房源列表获取成功');
    }
    
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
    public function getAllSelect(
        Request $request
    )
    {
        $res = curl(config('hosts.building').'/api/get_all_select?number='.$request->number,'GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 所有的楼座下拉数据
    public function buildingBlocksSelect()
    {
        // 获取登录人公司所在的城市
        $cityName = Company::find(Common::user()->company_guid)->name;
        $res = curl(config('hosts.building').'/api/building_blocks_all?city_name='.$cityName,'GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 变更人员
    public function changePersonnel(
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->changePersonnel($request);
        return $this->sendResponse($res,'变更人员成功');
    }

    // 通过楼座,楼层获取房源成功
    public function adoptConditionGetHouse(
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->adoptConditionGetHouse($request);
        return $this->sendResponse($res,'通过楼座,楼层获取房源成功');
    }

    // 房号验证
    public function houseNumberValidate(
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->houseNumberValidate($request);
        return $this->sendResponse($res,'房号验证操作成功');
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
