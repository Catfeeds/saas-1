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

        $data = ['电费'];



//        $res = \DB::select("SELECT * FROM `houses` WHERE JSON_CONTAINS(JSON_EXTRACT(cost_detail,'$[*].name'), '[电费]')");
//        dd($res);


//        SELECT * FROM `json_test` WHERE json_contains(JSON_EXTRACT(json_data,'$.city'), '[1]');

//        $res = \DB::table('houses')->where('cost_detail', ['江汉区', '武昌区'])->get();
//        dd($res);

        $res = \DB::table('houses')->whereRaw("JSON_CONTAINS(cost_detail,'Mysql')")->get();
        dd($res);

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
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->updateImg($request);
        if (!$res) return $this->sendError('修改房源图片失败');
        return $this->sendResponse($res,'修改房源图片成功');
    }
    
    // 房源置顶
    public function setTop
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->setTop($request);
        return $this->sendResponse($res,'房源置顶成功');
    }

    // 取消置顶
    public function cancelTop
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->cancelTop($request);
        return $this->sendResponse($res,'取消置顶成功');
    }

    // 通过楼座和楼层获取房源信息
    public function adoptAssociationGetHouse
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->adoptAssociationGetHouse($request);
        return $this->sendResponse($res,'通过楼座,楼层获取房源成功');
    }

    // 看房方式
    public function seeHouseWay(
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->seeHouseWay($request);
        if (empty($res)) return $this->sendError('看房方式添加失败');
        return $this->sendResponse($res,'看房方式添加成功');
    }

    // 转移房源
    public function transferHouse
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->transferHouse($request);
        if (!$res) return $this->sendError('转移房源失败');
        return $this->sendResponse($res,'转移房源成功');
    }
    
    // 转为公盘
    public function changeToPublic
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->changeToPublic($request);
        if (!$res) return $this->sendError('转为公盘失败');
        return $this->sendResponse($res,'转为公盘成功');
    }
    
    // 转为私盘
    public function switchToPrivate
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->switchToPrivate($request);
        if (!$res) return $this->sendError('转为私盘成功');
        return $this->sendResponse($res,'转为私盘成功');
    }

    // 转为无效
    public function turnedInvalid
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->turnedInvalid($request);
        if (!$res) return $this->sendError('转为无效失败');
        return $this->sendResponse($res,'转为无效成功');
    }
    
    // 转为有效
    public function turnEffective
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->turnEffective($request);
        if (!$res) return $this->sendError('转为有效失败');
        return $this->sendResponse($res,'转为有效成功');
    }

    // 修改证件图片
    public function relevantProves
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $res = $repository->relevantProves($request);
        if (!$res) return $this->sendError('修改证件图片失败');
        return $this->sendResponse(collect($request->relevant_proves_img)->map(function($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.qiniu_suffix'),
            ];
        }),'修改证件图片成功');
    }

    // 获取业主信息
    public function getOwnerInfo
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->getOwnerInfo($request);
        if (!$res) return $this->sendError('获取业主信息失败');
        return $this->sendResponse($res,'获取业主信息成功');
    }
    
    // 获取门牌号
    public function getHouseNumber
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->getHouseNumber($request);
        if (!$res) return $this->sendError('获取门牌号失败');
        return $this->sendResponse($res,'获取门牌号成功');
    }
}
