<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Customer;
use App\Models\House;
use App\Models\HouseOperationRecord;
use App\Models\SeeHouseWay;
use App\Models\Track;
use App\Models\User;
use App\Models\Visit;

class BusinessManageService
{
    // 业务服务列表
    public function BusinessList($request)
    {
        // 获取当前查看所有角色
        $usersGuid = Access::getUser(Common::user()->role->level);

        $users = User::whereIn('guid', $usersGuid);

        if ($request->name) {
            $users = $users->where('name', 'like', '%'.$request->name.'%');
        }

        if ($request->area_guid || $request->storefront_guid || $request->group_guid) {
            $companyFrameworksService = new CompanyFrameworksService();
            $guid = $companyFrameworksService->getUserAdoptCondition($request);
            $users = $users->whereIn('rel_guid', $guid);
        } else {
            $users = $users->where('company_guid', Common::user()->company_guid);
        }

        if (empty($request->created_at)) {
            $request->offsetSet('created_at', [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1day'))]);
        }

        // 获取所有角色相关信息
        $users = $users->withCount(['house' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customer' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['houseTrack' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customerTrack' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['houseVisit' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['customerVisit' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['seeHouseWay' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordImg' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordHouseNumber' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->withCount(['recordOwnerInfo' => function($query) use($request) {
                $query->whereBetween('created_at',$request->created_at);
            }])->paginate($request->per_page ?? 10);

        return $users;
    }


    // 获取当天时间
    public function getTime()
    {
        return  [date('Y-m-d', strtotime('now')), date('Y-m-d', strtotime('+1day'))];
    }

    // 新增房源
    public function getHouse($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $house = House::with('buildingBlock', 'buildingBlock.building', 'guardianPerson')
                        ->where(['guardian_person' => $request->user_guid])
                        ->whereBetween('created_at', $time);
        $house = $house->paginate($request->per_page??10);
        foreach ($house as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['guardianPerson'] = $v->guardianPerson->name;
            $data[$k]['house_name'] = $v->buildingBlock->building->name;
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
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $customer = Customer::with('guardianPerson')
                            ->where(['guardian_person' => $request->user_guid])
                            ->whereBetween('created_at', $time);
        $customer = $customer->paginate($request->per_page??10);
        foreach ($customer as $k => $v) {
            $data[$k]['guid'] = $v->guid;
            $data[$k]['guardianPerson'] = $v->guardianPerson->name;
            $data[$k]['name'] = $v->customer_info[0]['name'];
            $data[$k]['remarks'] = $v->remarks;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $customer->setCollection(collect($data));
    }

    // 房源跟进
    public function getHouseTrack($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $track = Track::with('house','house.buildingBlock', 'house.buildingBlock.building')
                        ->where(['model_type' => 'App\Models\House', 'user_guid' => $request->user_guid])
                        ->whereBetween('created_at', $time);
        $track = $track->paginate($request->per_page??10);
        foreach ($track as $k => $v) {
            $data[$k]['guid'] = $v->house->guid;
            $data[$k]['user'] = $v->user->name;
            $data[$k]['house_name'] = $v->house->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->house->house_identifier;
            $data[$k]['tracks_info'] = $v->tracks_info;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $track->setCollection(collect($data));
    }

    // 客源跟进
    public function getCustomerTrack($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $track = Track::with('customer')
                        ->where(['model_type' => 'App\Models\Customer', 'user_guid' => $request->user_guid])
                        ->whereBetween('created_at', $time);
        $track = $track->paginate($request->per_page??10);
        foreach ($track as $k => $v) {
            $data[$k]['guid'] = $v->customer->guid;
            $data[$k]['user'] = $v->user->name;
            $data[$k]['customer_name'] = $v->customer->customer_info[0]['name'];
//            $data[$k]['guid'] = $v->guid;  客源编号
            $data[$k]['tracks_info'] = $v->tracks_info;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $track->setCollection(collect($data));
    }

    // 房源带看
    public function getHouseVisit($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $visit = Visit::with('coverHouse','coverHouse.buildingBlock', 'coverHouse.buildingBlock.building', 'visitCustomer', 'accompanyUser')
                    ->where(['model_type' => 'App\Models\House', 'visit_user' => $request->user_guid])
                    ->whereBetween('created_at', $time);
        $visit = $visit->paginate($request->per_page??10);
        foreach ($visit as $k => $v) {
            $data[$k]['guid'] = $v->coverHouse->guid;
            $data[$k]['user'] = $v->user->name;
            $data[$k]['house'] = $v->coverHouse->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->coverHouse->house_identifier;
            $data[$k]['customer'] = $v->visitCustomer->customer_info[0]['name'];
//            $data[$k]['customer'] = $v->visitCustomerHouse->name; 客源编号
            $data[$k]['accompany'] = optional($v->accompanyUser)->name;
            $data[$k]['remarks'] = $v->remarks;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $visit->setCollection(collect($data));
    }

    // 客源带看
    public function getCustomerVisit($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $visit = Visit::with('coverCustomer','visitHouse.buildingBlock','visitHouse.buildingBlock.building','accompanyUser')
            ->where(['model_type' => 'App\Models\Customer','visit_user' => $request->user_guid])
            ->whereBetween('created_at',$time);
        $visit = $visit->paginate($request->per_page??10);
        foreach ($visit as $k => $v) {
            $data[$k]['guid'] = $v->coverCustomer->guid;
            $data[$k]['user'] = $v->user->name;
            $data[$k]['house'] = $v->visitHouse->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->visitHouse->house_identifier;
            $data[$k]['customer'] = $v->coverCustomer->customer_info[0]['name'];
//            $data[$k]['customer'] = $v->visitCustomerHouse->name; 客源编号
            $data[$k]['accompany'] = optional($v->accompanyUser)->name;
            $data[$k]['remarks'] = $v->remarks;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $visit->setCollection(collect($data));
    }

    // 提交钥匙
    public function getSeeHouseWay($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }
        $data = [];
        $seeHouseWay = SeeHouseWay::with('house','house.buildingBlock','house.buildingBlock.building','storefront')
            ->where(['user_guid' => $request->user_guid,'type' => 4])
            ->whereBetween('created_at',$time);

        $seeHouseWay = $seeHouseWay->paginate($request->per_page??10);

        foreach ($seeHouseWay as $k => $v) {
            $data[$k]['guid'] = $v->house->guid;
            $data[$k]['house'] = $v->house->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->house->house_identifier;
//            $data[$k]['customer'] = $v->visitCustomerHouse->name; 客源编号
            $data[$k]['remarks'] = $v->remarks;
            $data[$k]['storefront'] = $v->storefront->name;
            $data[$k]['received_time'] = $v->received_time;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');

        }
        return $seeHouseWay->setCollection(collect($data));
    }

    // 上传图片
    public function getRecordImg($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }

        $data = [];
        $recordImg = HouseOperationRecord::with('house','house.buildingBlock','house.buildingBlock.building','user')
            ->where(['user_guid' => $request->user_guid, 'type' => 3])
            ->whereBetween('created_at',$time);
        $recordImg = $recordImg->paginate($request->per_page??10);
        foreach ($recordImg as $k => $v) {
            $data[$k]['guid'] = $v->house->guid;
            $data[$k]['house'] = $v->house->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->house->house_identifier;
            $data[$k]['img'] = $v->img;
            $data[$k]['number'] = count($v->img);
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $recordImg->setCollection(collect($data));

    }

    // 房号
    public function getRecordHouseNumber($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }

        $data = [];
        $recordHouseNumber = HouseOperationRecord::with('house','house.buildingBlock','house.buildingBlock.building','user')->where('remarks','查看了房源的门牌号信息')
            ->where(['user_guid' => $request->user_guid,'type' => 4])
            ->whereBetween('created_at',$time);

        $recordHouseNumber = $recordHouseNumber->paginate($request->per_page??10);
        foreach ($recordHouseNumber as $k => $v) {
            $data[$k]['guid'] = $v->house->guid;
            $data[$k]['house'] = $v->house->buildingBlock->building->name;
            $data[$k]['house_identifier'] = $v->house->house_identifier;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $recordHouseNumber->setCollection(collect($data));
    }

    // 业主信息
    public function getRecordOwnerInfo($request)
    {
        if ($request->created_at) {
            $time = $request->created_at;
        } else {
            $time = $this->getTime();
        }

        $data = [];
        $recordOwnerInfo = HouseOperationRecord::with('house','house.buildingBlock','house.buildingBlock.building','user')->where('remarks','查看了房源的业主信息')
            ->where(['user_guid' => $request->user_guid,'type' => 4])
            ->whereBetween('created_at',$time);

        $recordOwnerInfo = $recordOwnerInfo->paginate($request->per_page??10);
        foreach ($recordOwnerInfo as $k => $v) {
            $data[$k]['guid'] = $v->house->guid;
            $data[$k]['house'] = $v->house->buildingBlock->building->name;
            $data[$k]['owner_info'] = $v->house->owner_info;
            $data[$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
        }
        return $recordOwnerInfo->setCollection(collect($data));
    }
}