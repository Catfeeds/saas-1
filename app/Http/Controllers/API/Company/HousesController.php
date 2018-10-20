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
        if (empty($guardian_person)) return $this->sendError('暂无权限');
        $res = $repository->houseList($request, $service, $guardian_person);
        return $this->sendResponse($res,'房源列表获取成功');
    }
    
    // 添加房源
    public function store
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        // 查询该公司房源是否存在
        $companyHouse = House::where([
            'company_guid' => Common::user()->company_guid,
            'building_block_guid' => $request->building_block_guid,
            'floor' => $request->floor,
            'house_number' => $request->house_number,
        ])->first();
        if ($companyHouse) {
            return $this->sendError('该公司已存在该房源');
        }

        // 判断云房源唯一性
        if ($request->share == 1) {
            $house = House::where([
                'floor' => $request->floor,
                'house_number' => $request->house_number,
                'building_block_guid' => $request->building_block_guid,
            ])->first();
            if ($house) {
                return $this->sendError('该房源已共享');
            }
        }

        // 判断是否上线
        if ($request->online == 2) {
            $house = House::where([
                'floor' => $request->floor,
                'house_number' => $request->house_number,
                'building_block_guid' => $request->building_block_guid,
            ])->first();
            if ($house) {
                return $this->sendError('该房源已上线');
            }
        }

        $res = $repository->addHouse($request);
        if (empty($res)) return $this->sendError('房源添加失败');
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
        $house->permission = $service->propertyPermission($house);
        return $this->sendResponse($house,'获取更新之前原始数据成功');
    }

    // 更新房源信息
    public function update
    (
        HousesRequest $request,
        House $house,
        HousesRepository $repository,
        HousesService $service
    )
    {
        $maintainer = Access::adoptGuardianPersonGetHouse('edit_house');
        if (!in_array($house->guid,$maintainer)) return $this->sendError('无编辑房源权限');
        // 获取房源编辑指定信息权限
        $permission = $service->propertyPermission($house);
        $res = $repository->updateHouse($house, $request, $permission,$service);
        return $this->sendResponse($res,'更新房源成功');
    }

    // 删除房源
    public function destroy
    (
        House $house,
        HousesService $service
    )
    {
        // 判断是否是自己的房子
        $user = Common::user()->guid;
        // 判断是否超过一小时
        $time = time() - strtotime($house->created_at->format('Y-m-d H:i:s'));
        if ($user != $house->guardian_person || $time > 60*60 ) return $this->sendError('无法删除该房源');
        $res = $service->delHouse($house);
        if (!$res) return $this->sendError('删除失败');
        return $this->sendResponse(true, '删除成功');
    }

    // 获取所有下拉数据
    public function getAllSelect(
        Request $request
    )
    {
        // 获取登录人公司所在的城市
        $cityGuid = Company::find(Common::user()->company_guid)->city_guid;
        $res = curl(config('hosts.building').'/api/get_all_select?number='.$request->number.'&city_guid='.$cityGuid,'GET');
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 所有的楼座下拉数据
    public function buildingBlocksSelect()
    {
        // 获取登录人公司所在的城市
        $cityGuid = Company::find(Common::user()->company_guid)->city_guid;
        $res = curl(config('hosts.building').'/api/building_blocks_all?city_guid='.$cityGuid,'GET');
        return $this->sendResponse($res->data, '获取所有下拉数据成功');
    }

    // 获取公司所在的区域
    public function companyArea()
    {
        // 获取登录人公司所在的区域
        $cityGuid = Company::find(Common::user()->company_guid)->city_guid;
        $res = curl(config('hosts.building').'/api/get_company_area?city_guid='.$cityGuid,'GET');
        return $this->sendResponse($res->data, '获取公司所在区域数据成功');
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
            if (empty($guardian_person)) return $this->sendError('暂无权限');
            // 判断权限范围
            if (!in_array($userGuid, $guardian_person)) return $this->sendError('暂无权限');
        } elseif ($request->guardian_person) {
            $guardian_person = Access::adoptGuardianPersonGetHouse('set_guardian_person');
            if (!in_array($request->house_guid, $guardian_person)) return $this->sendError('暂无修改维护人权限');
        } elseif ($request->pic_person) {
            $guardian_person = Access::adoptGuardianPersonGetHouse('set_pic_person');
            if (!in_array($request->house_guid, $guardian_person)) return $this->sendError('暂无修改图片人权限');
        } elseif ($request->key_person) {
            $guardian_person = Access::adoptGuardianPersonGetHouse('set_key_person');
            if (!in_array($request->house_guid, $guardian_person)) return $this->sendError('暂无修改钥匙人权限');
        }

        $res = $repository->changePersonnel($request);
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

        // 上传图片
        $uploadImage = Access::adoptGuardianPersonGetHouse('upload_pic');
        if (!in_array($house->guid, $uploadImage)) {
            $permission['upload_pic'] = false; // 是否允许上传图片
        }
        $res = $service->updateImg($request, $house->pic_person, array_merge($house->house_type_img??array(), $house->indoor_img??array(), $house->outdoor_img??array()));
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
        // 判断作用域
        $house = Access::adoptGuardianPersonGetHouse('set_guardian_person');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限转移房源');

        $res = $repository->transferHouse($request);
        return $this->sendResponse($res,'房源转移成功');
    }
    
    // 转为公盘
    public function changeToPublic
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('private_to_public');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限更改盘别');

        $res = $repository->changeToPublic($request);
        return $this->sendResponse($res, '房源转公盘成功');
    }
    
    // 转为私盘
    public function switchToPrivate
    (
        HousesRequest $request,
        HousesRepository $repository
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('public_to_private');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限更改盘别');

        $res = $repository->switchToPrivate($request);
        return $this->sendResponse($res, '房源转私盘成功');
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
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('upload_document');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限房源证件图片');

        $res = $repository->relevantProves($request);
        if (!$res) return $this->sendError('证件上传失败');
        return $this->sendResponse(collect($request->relevant_proves_img)->map(function($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img,
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

    // 房源共享
    public function shareHouse
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('house_share');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限共享该房源');

        $res = $service->shareHouse($request);
        if (!$res) return $this->sendError('房源共享失败');
        return $this->sendResponse($res, '房源共享成功');
    }

    // 下架共享房源
    public function unShare
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('house_share');
        if (!in_array($request->guid, $house)) return $this->sendError('无权限下架该房源');

        $res = $service->unShare($request);
        if (!$res) return $this->sendError('房源下架失败');
        return $this->sendResponse($res, '房源下架成功');
    }

    // 房源上线
    public function online
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('house_online');
        if (!in_array($request->guid,$house)) return $this->sendError('无权限上线该房源');
        $res = $service->onlineHouse($request);
        if (!$res) return $this->sendError('房源上线失败');
        return $this->sendResponse($res,'房源上线成功');
    }
    
    // 房源下线
    public function offline
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        // 判断权限
        $house = Access::adoptGuardianPersonGetHouse('house_online');
        if (!in_array($request->guid,$house)) return $this->sendError('无权限下线该房源');
        $res = $service->offlineHouse($request);
        if (!$res) return $this->sendError('房源下线失败');
        return $this->sendResponse($res,'房源下线成功');
    }
    
    // 上线房源列表
    public function onlineHouseList
    (
        Request $request,
        HousesService $service
    )
    {
        // 通过权限获取区间用户
        $guardian_person = Access::adoptPermissionGetUser('house_list');
        if (empty($guardian_person)) return $this->sendError('暂无权限');
        $res = $service->getOnlineList($request);
        return $this->sendResponse($res,'房源列表获取成功');
    }

    // 上线房源详情
    public function onlineShow
    (
        HousesRequest $request,
        HousesService $service
    )
    {
        $res = $service->getOnlineInfo($request->guid);
        return $this->sendResponse($res,'上线房源详情获取成功');
    }
}
