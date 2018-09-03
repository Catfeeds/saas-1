<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Block;
use App\Models\Building;
use App\Models\Customer;
use App\Models\CustomerOperationRecord;

class CustomersService
{
    //客源列表
    public function getList($request)
    {
        return Customer::where([
            'company_guid' => Common::user()->company_guid,
            'status' => 1
        ])->paginate($request->per_page??10);
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
        $customer->track_time = $request->track_time;
        if (!$customer->save()) return false;
        return true;
    }

    public function getCustomerInfo($guid)
    {
        $res = Customer::with('entryPerson:guid,name,tel', 'guardianPerson:guid,name,tel', 'track', 'track.user', 'remind')->where('guid', $guid)->first();
        $data = [];
        $data['guid'] = $res->guid;
        $data['level'] = $res->level_cn;
        $data['guest'] = $res->guest_cn;
        $data['title'] = $res->prince_cn.',' .$res->acreage_cn;
        $data['customer_info'] = $res->customer_info;
        $data['area'] = $res->intention;
        // 意向楼盘
        $data['building'] = Building::whereIn('guid', $res->building)->pluck('name')->toArray();
        // 查询意向商圈
        $data['block'] = Block::whereIn('guid', $res->block)->pluck('name')->toArray();
        $data['house_type'] = $res->house_type;
        $data['acreage'] = $res->acreage_cn;
        $data['price'] = $res->price_cn;
        $data['floor'] = $res->floor_cn;
        $data['type'] = $res->type_cn;
        $data['renovation'] = $res->renovation_cn;
        $data
    }

    // 客源转为无效/有效
    public function invalid($request)
    {
        \DB::beginTransaction();
        try {
            $data = ['status' => $request->status];
            if ($request->status != 1 && $request->status != 2) {
                $data['invalid_reason'] = $request->invalid_reason;
            }
            $suc =  Customer::where('guid', $request->guid)->update($data);
            if (!$suc) throw new \Exception('客源转为有效/无效失败');

            $res = Common::customerOperationRecords(Common::user()->guid,$request->guid,4,$request->remarks);

            if (!$res) throw new \Exception('客源操作记录添加失败');
            \DB::commit();
            return true;
        } catch(\Exception $exception) {
            \DB::rollback();
            \Log::error('客源设置失败'.$exception->getMessage());
            return false;
        }
    }

    // 更改客源类型(公私盘)
    public function updateGuest($request)
    {
        \DB::beginTransaction();
        try {
           $suc =  Customer::where('guid', $request->guid)->update(['guest' => $request->guest]);
           if (!$suc) throw new \Exception('房源类型更改失败');
           $res = Common::customerOperationRecords(Common::user()->guid,$request->guid,4,$request->remarks);
           if (!$res) throw new \Exception('客源操作记录添加失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('房源类型更改失败'.$exception->getMessage());
            return false;
        }
    }

    // 转移客源,变更人员
    public function transfer($request)
    {
        $customer = Customer::where(['guid', $request->guid]);

        if ($request->broker) {
            return $customer->update(['guardian_person' => $request->broker]);
        } elseif ($request->entry_person) {
            return $customer->update(['entry_person' => $request->entry_person]);
        } elseif ($request->guardian_person) {
            return $customer->updata(['guardian_person' => $request->guardian_person]);
        }
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