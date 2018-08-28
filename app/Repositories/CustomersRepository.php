<?php

namespace App\Repositories;


use App\Handler\Common;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomersRepository extends Model
{
    //客源列表
    public function getList($request)
    {
        return Customer::where('company_guid', Common::user()->company_guid)->paginate($request->per_page??10);
    }

    //添加客源
    public function addCustomer($request)
    {
        return Customer::create([
            'guid' => Common::getUuid(),
            'company_guid' => Common::user()->company_guid,
            'level' => $request->level,
            'guest' => $request->guest,
            'customer_info' => $request->customer_info,
            'remarks' => $request->remarks,
            'intention' => $request->intention,
            'block' => $request->block,
            'building' => $request->building,
            'house_type' => $request->house_type,
            'price' => $request->price,
            'acreage' => $request->acreage,
            'type' => $request->type,
            'renovation' => $request->renovation,
            'floor' => $request->floor,
            'target' => $request->target,
            'status' => 1,
            'entry_person' => Common::user()->guid,
            'guardian_person' => Common::user()->guid,
            'track_time' => date('Y-m-d H:i:s',time())  // 第一次跟进时间
        ]);
    }

    //更新客源
    public function updateCustomer($customer, $request)
    {
        $customer->level = $request->level;
        $customer->guest = $request->guest;
        $customer->customer_info = $request->customer_info;
        $customer->remarks = $request->remarks;
        $customer->intention = $request->intention;
        $customer->block = $request->block;
        $customer->building = $request->building;
        $customer->house_type = $request->house_type;
        $customer->price = $request->price;
        $customer->acreage = $request->acreage;
        $customer->type = $request->type;
        $customer->renovation = $request->renovation;
        $customer->floor = $request->floor;
        $customer->target = $request->targets;
        if (!$customer->save()) return false;
        return true;
    }

    //客源转为无效
    public function invalid($guid, $request)
    {
        return Customer::where('guid', $guid)->update([
            'status' => $request->status,
            'reason' => $request->reason
        ]);
    }

    //更改客源类型(公私盘)
    public function updateGuest($guid, $request)
    {
        return Customer::where('guid', $guid)->update(['guest' => $request->guest]);
    }

    //转移客源
    public function transfer($guid, $request)
    {
        return Customer::where('guid', $guid)->update(['guardian_person' => $request->guardian_person]);
    }

}