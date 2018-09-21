<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Customer;
use App\Models\House;
use App\Models\Track;
use App\Models\User;
use App\Models\Visit;

class BusinessManageService
{
    protected  $user;

    public function __construct()
    {
        $this->user = Common::user();
    }


    public function BusinessList($request)
    {
        // 获取当前查看所有角色
        $usersGuid = Access::getUser(Common::user()->role->level);

        // 获取所有角色相关信息
        $users = User::whereIn('guid', $usersGuid)
            ->withCount([
                'house',    // 房源
                'customer', // 客源
                'houseTrack',  // 房源跟进
                'customerTrack',   // 客源跟进
                'houseVisit',  // 房源带看
                'customerVisit',   // 客源带看
                'seeHouseWay',  // 提交钥匙
                'recordImg',    // 上传图片
                'recordHouseNumber',    // 房号
                'recordOwnerInfo'   // 业主信息
            ])->paginate($request->per_page ?? 10);

        return $users;
    }



    // 通过姓名获取guid
    public function getUserGuid($name)
    {
        return User::where('name', 'like', '%'.$name.'%')->pluck('guid')->toArray();
    }

    // 新增房源
    public function getHouse($request)
    {
        $data = [];
        // 同公司下的房子
        $house = House::with('buildingBlock', 'buildingBlock.building', 'guardianPerson')->where('company_guid', $this->user->company_guid)->whereBetween('created_at', $request->time);
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
        $house = $house->paginate(5);
        foreach ($house as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['user'] = $v->guardianPerson->name;
            $data[$k]['name'] = $v->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->house_identifier;
            $data[$k]['img'] = $v->indoor_img_cn;
            $data[$k]['acreage'] = $v->acreage_cn;
            $data[$k]['price'] = $v->price.'元/㎡·月';
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $house->setCollection(collect($data));
    }

    // 获取客源
    public function getCustomer($request)
    {
        $data = [];
        // 同公司下的客源
        $customer = Customer::where('company_guid', $this->user->company_guid);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $customer = $customer->whereIn('guardian_person', $user_guid);
        }

        // 范围
//        if ($request->rang) {
//
//        }
        $customer = $customer->paginate(5);
        foreach ($customer as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['name'] = $v->customer_info[0]['name'];
            $data[$k]['remarks'] = $v->remarks;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $customer->setCollection(collect($data));
    }

    // 房源跟进
    public function getHouseTrack($request)
    {
        $data = [];
        $track = Track::with('house','house.buildingBlock', 'house.buildingBlock.building')->where('model_type','App\Models\House')->whereBetween('created_at', $request->time);

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
        foreach ($track as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['guid'] = $v->house->buildingBlock->building->name;
            $data[$k]['guid'] = $v->house_identifier;
            $data[$k]['tracks_info'] = $v->tracks_info;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $track->setCollection(collect($data));
    }

    // 客源跟进
    public function getCustomerTrack($request)
    {
        $data = [];
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
        foreach ($track as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['name'] = $v->customer_info[0]['name'];
//            $data[$k]['guid'] = $v->guid;  客源编号
            $data[$k]['tracks_info'] = $v->tracks_info;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $track->setCollection(collect($data));
    }

    // 房源带看
    public function getHouseVisit($request)
    {
        $data = [];
        $visit = Visit::with('cover','cover.buildingBlock', 'cover.buildingBlock.building', 'visitCustomerHouse', 'accompanyUser')->where('model_type', 'App\Models\House')->whereBetween('created_at', $request->time);

        // 姓名
        if ($request->name) {
            $user_guid = $this->getUserGuid($request->name);
            $visit = $visit->where('visit_user', $user_guid);
        }
//        // 范围
//        if ($request->rang) {
//
//        }
        $visit = $visit->paginate(5);
        foreach ($visit as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['house'] = $v->cover->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->cover->house_identifier;
            $data[$k]['customer'] = $v->visitCustomerHouse->customer_info[0]['name'];
//            $data[$k]['customer'] = $v->visitCustomerHouse->name; 客源编号
            $data[$k]['accompany'] = optional($v->accompanyUser)->name;
            $data[$k]['remarks'] = $v->remarks;
        }
        return $visit->setCollection(collect($data));
    }

    // 客源带看
    public function getCustomerVisit
    (

    )
    {

    }

    // 提交钥匙
    public function SeeHouseWay($request)
    {

    }

    // 上传图片
    public function RecordImg($request)
    {

    }

    // 房号
    public function recordHouseNumber($request)
    {

    }

    // 业主信息
    public function recordOwnerInfo($request)
    {

    }
}