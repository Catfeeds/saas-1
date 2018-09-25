<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Models\Customer;
use App\Models\House;
use App\Models\SeeHouseWay;
use App\Models\Track;
use App\Models\User;
use App\Services\BusinessManageService;
use Illuminate\Http\Request;

class BusinessManageController extends APIBaseController
{
    // 业务服务列表
    public function index(
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->BusinessList($request);
        return $this->sendResponse($res,'业务服务列表获取成功');
    }

    // 新增房源
    public function getHouse
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getHouse($request);
        return $this->sendResponse($res, '获取成功');
    }

    // 获取客源
    public function getCustomer
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getCustomer($request);
        return $this->sendResponse($res, '获取成功');
    }
    
    
    // 房源跟进
    public function getHouseTrack
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getHouseTrack($request);
        return $this->sendResponse($res, '获取成功');
    }

    // 客源跟进
    public function getCustomerTrack
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getCustomerTrack($request);
        return $this->sendResponse($res, '获取成功');
    }

    // 房源带看
    public function getHouseVisit
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getHouseVisit($request);
        return $this->sendResponse($res, '获取成功');
    }
    
    // 客源带看
    public function getCustomerVisit
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getCustomerVisit($request);
        return $this->sendResponse($res,'获取成功');
    }

    // 提交钥匙
    public function getSeeHouseWay
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getSeeHouseWay($request);
        return $this->sendResponse($res,'获取成功');
    }

    // 上传图片
    public function getRecordImg
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getRecordImg($request);
        return $this->sendResponse($res,'获取成功');
    }

     // 查看房号
    public function getRecordHouseNumber
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getRecordHouseNumber($request);
        return $this->sendResponse($res,'获取成功');
    }

    // 查看业主信息
    public function getRecordOwnerInfo
    (
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->getRecordOwnerInfo($request);
        return $this->sendResponse($res,'获取成功');
    }
}
