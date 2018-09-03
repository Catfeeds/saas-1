<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;

class CustomersRepository extends Model
{
    // 客源列表
    public function getList($request)
    {
        return Customer::where('company_guid', Common::user()->company_guid)->paginate($request->per_page??10);
    }

    // 添加客源
    public function addCustomer($request)
    {
        return Customer::create([
            'guid' => Common::getUuid(),
            'company_guid' => Common::user()->company_guid,
            'level' => $request->level,
            'guest' => $request->guest,
            'customer_info' => Common::arrayToObject($request->customer_info),
            'remarks' => $request->remarks,
            'intention' => Common::arrayToObject($request->intention),
            'block' => Common::arrayToObject($request->block),
            'building' => Common::arrayToObject($request->building),
            'house_type' => Common::arrayToObject($request->house_type),
            'min_price' => $request->min_price,
            'max_price' => $request->max_price,
            'min_acreage' => $request->min_acreage,
            'max_acreage' => $request->max_acreage,
            'type' => $request->type,
            'renovation' => $request->renovation,
            'min_floor' => $request->min_floor,
            'max_floor' => $request->max_floor,
            'status' => 1,
            'entry_person' => Common::user()->guid,
            'guardian_person' => Common::user()->guid,
            'track_time' => date('Y-m-d H:i:s',time())  // 第一次跟进时间
        ]);
    }

    // 更新客源
    public function updateCustomer($customer, $request)
    {
        $customer->level = $request->level;
        $customer->guest = $request->guest;
        $customer->customer_info = Common::arrayToObject($request->customer_info);
        $customer->remarks = $request->remarks;
        $customer->intention = Common::arrayToObject($request->intention);
        $customer->block = Common::arrayToObject($request->block);
        $customer->building = Common::arrayToObject($request->building);
        $customer->house_type = Common::arrayToObject($request->house_type);
        $customer->min_price = $request->min_price;
        $customer->max_price = $request->max_price;
        $customer->min_acreage = $request->min_acreage;
        $customer->max_acreage = $request->max_acreage;
        $customer->type = $request->type;
        $customer->renovation = $request->renovation;
        $customer->min_floor = $request->min_floor;
        $customer->max_floor = $request->max_floor;
        $customer->status = $request->status;
        $customer->track_time = $request->track_time;
        if (!$customer->save()) return false;
        return true;
    }

    // 客源转为无效
    public function invalid($guid, $request)
    {
        return Customer::where('guid', $guid)->update([
            'status' => $request->status,
            'reason' => $request->reason
        ]);
    }

    // 更改客源类型(公私盘)
    public function updateGuest($guid, $request)
    {
        return Customer::where('guid', $guid)->update(['guest' => $request->guest]);
    }

    // 转移客源
    public function transfer($guid, $request)
    {
        return Customer::where('guid', $guid)->update(['guardian_person' => $request->guardian_person]);
    }


    // 获取正常状态的客源下拉数据
    public function normalCustomer()
    {
        $res = Customer::where([
            'company_guid' => Common::user()->company_guid,
            'status' => 1
        ])->get();
        return $res->map(function ($v){
            return [
                'value' => $v->guid,
                'label' => '客户:'.$v->customer_info[0]['name'].'  电话:'.$v->customer_info[0]['tel']
            ];
        });
    }
}