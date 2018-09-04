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
        ])->with('guardianPerson:guid,name', 'entryPerson:guid,name')->paginate($request->per_page??10);
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

    // 客源详情
    public function getCustomerInfo($guid)
    {
        $res = Customer::with('entryPerson:guid,name,tel', 'guardianPerson:guid,name,tel', 'track', 'track.user', 'visit')->where('guid', $guid)->first();
        $data = [];
        $data['guid'] = $res->guid;
        $data['level'] = $res->level_cn;
        $data['guest'] = $res->guest_cn;
        $data['title'] = $res->price_interval_cn.',' . $res->acreage_interval_cn. '的写字楼。'. $res->remarks;
        $data['status'] = $res->status;
        $data['customer_info'] = $res->customer_info;
        $data['area'] = Common::splicing($res->intention); // 意向区域
        // 意向楼盘
        $item  = Building::whereIn('guid', $res->building)->pluck('name')->toArray();
        $data['building'] = Common::splicing($item);
        // 意向商圈
        $item = Block::whereIn('guid', $res->block)->pluck('name')->toArray();
        $data['block'] = Common::splicing($item);
        $data['house_type'] = Common::splicing($res->house_type); // 户型
        $data['acreage'] = $res->acreage_interval_cn; // 面积
        $data['price'] = $res->price_interval_cn; // 价格
        $data['floor'] = $res->floor_cn; // 楼层
        $data['type'] = $res->type_cn; // 房屋类型
        $data['renovation'] = $res->renovation_cn;
        $data['entry_person'] = $res->entryPerson;  // 录入人信息
        $data['guardian_person'] = $res->guardianPerson; // 维护人
        $data['created_at'] = $res->created_at->format('Y-m-d H:i:s');

        // 获取动态(跟进,带看) 最新4条数据
        $item = CustomerOperationRecord::where('customer_guid', $guid)
                                        ->whereIn('type', [1, 2])
                                        ->latest()
                                        ->take(4)
                                        ->pluck('type','created_at')
                                        ->toArray();
        $dynamic = [];
        foreach ($item as $k => $v) {
            if ($v == 1) {
                // 1跟进
                $dynamic[] = Track::with('user:guid,name')->where([
                    'model_type' => 'App\Models\Customer',
                    'rel_guid' => $guid,
                    'created_at' => $k
                ])->first();
            } else {
                // 2 带看
                $dynamic[] = Visit::with('user:guid,name', 'house')->where([
                    'cover_rel_guid' => $guid,
                    'model_type' => 'App\Models\Customer',
                    'created_at' => $k
                ])->first();
            }
        }
        $record = array_values(array_filter($dynamic));
        if (!empty($record)) {
            foreach ($record as $k =>  $v) {
                if (!empty($v)) {
                    $data['dynamic'][$k]['guid'] = $v->guid; // guid
                    $data['dynamic'][$k]['house_guid'] = optional($v->house)->guid; // 房源guid
                    $data['dynamic'][$k]['user_name'] = $v->user->name; // 跟进人/带看人
                    $data['dynamic'][$k]['remarks'] = $v->remarks ? $v->remarks : $v-> tracks_info;
                    $data['dynamic'][$k]['img_cn'] = optional($v->house)->indoor_img_cn;
                    $data['dynamic'][$k]['title'] = $v->house ? Common::HouseTitle($v->house->guid) : null;
                    $data['dynamic'][$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
                    // 是否允许编辑
                    $data['dynamic'][$k]['operation'] = false;
                    if (time() - strtotime($v->created_at->format('Y-m-d H:i')) <= 10 * 60 * 30) {
                        $guid = $v->user_guid ? $v->user_guid : $v->visit_user;
                        if ( $guid == Common::user()->guid) {
                            $data['dynamic'][$k]['operation'] = true;
                        }
                    }
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
            if ($request->status ==1) {
                $res = Common::customerOperationRecords(Common::user()->guid,$request->guid,4,'转为有效');
            } else {
                $res = Common::customerOperationRecords(Common::user()->guid,$request->guid,4,'将客源转为无效：原因是:'
                    . $request->status . $request->invalid_reason);
            }

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
            return $customer->update(['guardian_person' => $request->guardian_person]);
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
    
    // 获取客源信息
    public function getCustomersInfo($request)
    {
        \DB::beginTransaction();
        try {
            $customer_info = Customer::where(['guid' => $request->guid])->pluck('customer_info')->first();
            if (empty($customer_info)) throw new \Exception('获取客源信息失败');

            $customerOperationRecords = Common::customerOperationRecords(Common::user()->guid,$request->guid,3,'查看了客源联系方式');
            if (empty($customerOperationRecords)) throw new \Exception('查看客源信息添加操作记录失败');

            \DB::commit();
            return $customer_info;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }
}