<?php

namespace App\Http\Controllers\API\Company;

use App\Http\Controllers\API\APIBaseController;
use App\Models\Customer;
use App\Models\House;
use App\Models\Track;
use App\Models\User;
use App\Services\BusinessManageService;
use Illuminate\Http\Request;

class BusinessManageController extends APIBaseController
{
    // 操作记录
    public function index(
        Request $request,
        BusinessManageService $service
    )
    {
        $res = $service->BusinessList($request);
        return $this->sendResponse($res,'业务服务列表获取成功');
    }

    // 通过姓名获取guid
    public function getUserGuid($name)
    {
        return User::where('name', 'like', '%'.$name.'%')->pluck('guid')->toArray();
    }
    

    // 新增房源
    public function getHouse($request, $company_guid)
    {
        // 同公司下的房子
        $house = House::where('company_guid', $company_guid)->whereBetween('created_at', $request->time);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $house = $house->whereIn('guardian_person', $user_guid);
        }

//        // 范围
//        if ($request->rang) {
//            $guardian_person = $this->
//            $house = $house->whereIn('guardian_person', $guardian_person);
//        }
        return $house->paginate(5);
    }

    // 获取客源
    public function getCustomer($request, $company_guid)
    {
        // 同公司下的客源
        $customer = Customer::where('company_guid', $company_guid);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $customer = $customer->whereIn('guardian_person', $user_guid);
        }

        // 范围
//        if ($request->rang) {
//
//        }
        return $customer->paginate(5);
    }

    // 房源跟进
    public function getHouseTrack($request)
    {
        $track = Track::with('house')->where('model_type','App\Models\House')->whereBetween('created_at', $request->time);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $track = $track->where('user_guid', $user_guid);
        }

//        // 范围
//        if ($request->rang) {
//
//        }

        $track = $track->paginate(5);

    }

    // 客源跟进
    public function getCustomerTrack($request)
    {
        $track = Track::with('customer')->where('model_type','App\Models\Customer')->whereBetween('created_at', $request->time);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $track = $track->where('user_guid', $user_guid);
        }

//        // 范围
//        if ($request->rang) {
//
//        }

        $track = $track->paginate(5);
    }

    // 


}
