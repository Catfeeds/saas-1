<?php

namespace App\Http\Controllers\API\Company;

use App\Handler\Access;
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
        // 通过权限获取区间用户
        $guardian_person = Access::adoptPermissionGetUser('house_list');
        if (empty($guardian_person['status'])) return $this->sendError($guardian_person['message']);
        $res = $repository->houseList($request, $service, $guardian_person['message']);
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
        return $this->sendResponse($house,'获取1更新之前原始数据成功');
    }

    // 更新房源信息
    public function update
    (
        HousesRequest $request,
        House $house,
        HousesRepository $repository
    )
    {
        $maintainer = Access::adoptGuardianPersonGetHouse('edit_house');
        if (!in_array($house->guid,$maintainer)) return $this->sendError('无编辑房源权限');
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
    public function changePersonnel
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        // 判断是否有对应权限
        if ($request->entry_person) {
            $userGuid = $request->entry_person;
            $guardian_person = Access::adoptPermissionGetUser('set_entry_person');
        } elseif ($request->guardian_person) {
            $userGuid = $request->guardian_person;
            $guardian_person = Access::adoptPermissionGetUser('set_guardian_person');
        } elseif ($request->pic_person) {
            $userGuid = $request->pic_person;
            $guardian_person = Access::adoptPermissionGetUser('set_pic_person');
        } elseif ($request->key_person) {
            $userGuid = $request->key_person;
            $guardian_person = Access::adoptPermissionGetUser('set_key_person');
        }
        if (empty($guardian_person['status'])) return $this->sendError($guardian_person['message']);

        // 判断权限范围
        if (!in_array($userGuid, $guardian_person['message'])) return $this->sendError('暂无权限');

        $res = $repository->changePersonnel($request, $guardian_person['message']);
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse(true, $res['message']);
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
        $house = House::find($request->guid);
        // 通过权限获取区间用户
        $permission = Access::adoptPermissionGetUser('edit_pic');
        if (empty($permission['status'])) return $this->sendError($permission['message']);
        if (!in_array($house->guardian_person, $permission['message'])) return $this->sendError('无权限修改房源图片信息');
        $res = $service->updateImg($request,$permission['message'],$house->pic_person);
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
        // 通过权限获取区间用户
        $permission = Access::adoptGuardianPersonGetHouse('set_top');
        if (!in_array($request->guid, $permission)) return $this->sendError('无房源置顶权限');
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
        // 通过权限获取区间用户
        $permission = Access::adoptGuardianPersonGetHouse('set_top');
        if (!in_array($request->guid, $permission)) return $this->sendError('无房源取消置顶权限');
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
        $guardian_person = Access::adoptGuardianPersonGetHouse('set_guardian_person');
        if (!in_array(Common::user()->guid,$guardian_person['message'])) return $this->sendError('无权限转移房源');
        $res = $repository->transferHouse($request, $guardian_person['message']);
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse(true,$res['message']);
    }
    
    // 转为公盘
    public function changeToPublic
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $guardian_person = Access::adoptPermissionGetUser('private_to_public');
        if (empty($guardian_person['status'])) return $this->sendError($guardian_person['message']);
        if (!in_array(Common::user()->guid,$guardian_person['message'])) return $this->sendError('无权限变更盘别');
        $res = $repository->changeToPublic($request, $guardian_person['message']);
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse(true,$res['message']);
    }
    
    // 转为私盘
    public function switchToPrivate
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        $guardian_person = Access::adoptPermissionGetUser('public_to_private');
        if (empty($guardian_person['status'])) return $this->sendError($guardian_person['message']);
        if (!in_array(Common::user()->guid,$guardian_person['message'])) return $this->sendError('无权限变更盘别');
        $res = $repository->switchToPrivate($request, $guardian_person['message']);
        if (!$res['status']) return $this->sendError($res['message']);
        return $this->sendResponse(true,$res['message']);
    }

    // 转为无效
    public function turnedInvalid
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        // 通过权限获取区间用户
        $guardian_person = Access::adoptGuardianPersonGetHouse('update_house_status');
        if (!in_array($request->guid,$guardian_person)) return $this->sendError('无权限修改房源状态信息');
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
        // 通过权限获取区间用户
        $guardian_person = Access::adoptGuardianPersonGetHouse('update_house_status');
        if (!in_array($request->guid,$guardian_person)) return $this->sendError('无权限修改房源状态信息');
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
        // 通过权限获取区间用户
        $guardian_person = Access::adoptPermissionGetUser('upload_document');
        if (empty($guardian_person['status'])) return $this->sendError($guardian_person['message']);
        if (!in_array(Common::user()->guid,$guardian_person['message'])) return $this->sendError('无权限上传房源证件');
        $res = $repository->relevantProves($request, $guardian_person['message']);
        if (!$res['status']) return $this->sendError($res['message']);
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
