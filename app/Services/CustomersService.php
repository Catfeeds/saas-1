<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Block;
use App\Models\Building;
use App\Models\Customer;
use App\Models\CustomerOperationRecord;
use App\Models\Track;
use App\Models\Visit;

class CustomersService
{
    //客源列表
    public function getList($request)
    {
        return Customer::where([
            'company_guid' => 'ed8090e4a6b811e8bf9a416618026100',
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
            'customer_info' => $request->customer_info,
            'remarks' => $request->remarks,
            'intention' => $request->intention,
            'block' => $request->block,
            'building' => $request->building,
            'house_type' => $request->house_type,
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
        $customer->customer_info = $request->customer_info;
        $customer->remarks = $request->remarks;
        $customer->intention = $request->intention;
        $customer->block = $request->block;
        $customer->building = $request->building;
        $customer->house_type = $request->house_type;
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
        $data['title'] = $res->price_interval_cn.',' . $res->acreage_interval_cn;
        $data['customer_info'] = $res->customer_info;
        // 意向区域
        $area = '';
        if ($res->intention) {
            foreach ($res->intention as $v) {
                $area .= ','.$v['name'];
            }
        }
        $data['area'] = trim($area,',');
        // 意向楼盘
        $building = '';
        $item  = Building::whereIn('guid', $res->building)->pluck('name')->toArray();
        if (!empty($item)) {
            foreach ($item as $v) {
                $building .= ','.$v;
            }
        }
        $data['building'] = trim($building,',');
        // 查询意向商圈
        $item = Block::whereIn('guid', $res->block)->pluck('name')->toArray();
        $block = '';
        if (!empty($item)) {
            foreach ($item as $v) {
                $block .= ','.$v;
            }
        }
        $data['block'] = trim($block,',');
        $house_type = '';
        if ($res->house_type) {
            foreach ($res->house_type as $v) {
                $house_type .= ','.$v['name'];
            }
        }
        $data['house_type'] = trim($house_type,',');
        $data['acreage'] = $res->acreage_interval_cn;
        $data['price'] = $res->price_interval_cn;
        $data['floor'] = $res->floor_cn;
        $data['type'] = $res->type_cn;
        $data['renovation'] = $res->renovation_cn;
        $data['entry_person'] = $res->entryPerson;  // 录入人信息
        $data['guardian_person'] = $res->guardianPerson; // 维护人

        // 获取动态(跟进,带看) 最新4条数据
        $item = CustomerOperationRecord::where('customer_guid', $guid)
                                        ->whereIn('type', [1, 2])
                                        ->latest()
                                        ->take(4)
                                        ->get();
        $dynamic = [];
        foreach ($item as $v) {
            if ($v->type == 1) {
                // 1跟进
                $dynamic[] = Track::with('user:guid,name')->where([
                    'model_type' => 'App\Models\Customer',
                    'rel_guid' => $guid,
                    'created_at' => $v->created_at->format('Y-m-d H:i:s')
                ])->first();
            } else {
                // 2 带看
                $dynamic[] = Visit::with('user:guid,name', 'house')->where([
                    'cover_rel_guid' => $guid,
                    'model_type' => 'App\Models\Customer',
                    'created_at' => $v->created_at->format('Y-m-d H:i:s')
                ])->first();
            }
        }
        if (!empty($dynamic)) {
            foreach ($dynamic as $k =>  $v) {
                if (!empty($v)) {
                    $data['dynamic'][$k]['user_name'] = $v->user->name;
                    $data['dynamic'][$k]['remarks'] = $v->remarks ? $v->remarks : $v-> tracks_info;
                    $data['dynamic'][$k]['img_cn'] = optional($v->house)->indoor_img_cn;
                    $data['dynamic'][$k]['title'] = optional($v->house)->floor;
                }
            }
        }
        return $data;
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

    // 更改客源类型(公私客)
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
        $customer = Customer::where(['guid' => $request->guid]);

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