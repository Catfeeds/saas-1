<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Common;
use App\Http\Controllers\API\APIBaseController;
use App\Http\Controllers\Traits\QiNiu;
use App\Http\Requests\Company\HousesRequest;
use App\Models\Company;
use App\Models\House;
use App\Repositories\HousesRepository;
use App\Services\HousesService;
use Illuminate\Http\Request;

class HousesController extends APIBaseController
{
    use QiNiu;

    // 房源列表
    public function index(
        Request $request,
        HousesRepository $repository,
        HousesService $service
    )
    {
        $res = $repository->houseList($request, $service);
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

    // 房源详情
    public function show(
        House $house,
        HousesService $service
    )
    {
        $res = $service->getHouseInfo($house);
        return $this->sendResponse($res,'房源详情获取成功');
    }

    // 获取更新之前原始数据
    public function edit
    (
        House $house,
        HousesService $service
    )
    {
        $house->allGuid = $service->adoptBuildingBlockGetCity($house->building_block_guid);
        return $this->sendResponse($house,'获取更新之前原始数据成功');
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
        // 获取登录人公司所在的城市
        $cityName = Company::find(Common::user()->company_guid)->city_name;
        $res = curl(config('hosts.building').'/api/get_all_select?number='.$request->number.'&city_name='.$cityName,'GET');
        if (empty($res->data)) return $this->sendError($res->message);
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 所有的楼座下拉数据
    public function buildingBlocksSelect()
    {
        // 获取登录人公司所在的城市
        $cityName = Company::find(Common::user()->company_guid)->city_name;
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
    
    // 房源置顶
    public function setTop
    (
        $guid,
        HousesRepository $repository
    )
    {
        $res = $repository->setTop($guid);
        return $this->sendResponse($res,'房源置顶成功');
    }

    // 通过楼座和房号获取房源信息
    public function adoptAssociationGetHouse
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->adoptAssociationGetHouse($request);
        return $this->sendResponse($res,'通过楼座，房号获取房源成功');
    }
}
