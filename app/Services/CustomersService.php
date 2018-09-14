<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Customer;
use App\Models\CustomerOperationRecord;
use App\Models\Track;
use App\Models\Visit;

class CustomersService
{
    // 客源列表
    public function getList($request)
    {
        // 公客范围
        $public = Access::adoptPermissionGetUser('public_customer_show');
        if (empty($public['status'])) {
            $publicWhere = [];
        } else {
            $publicWhere = $public['message'];
        }

        // 私客范围
        $private = Access::adoptPermissionGetUser('private_customer_show');
        if (empty($private['status'])) {
            $privateWhere = [];
        } else {
            $privateWhere = $private['message'];
        }

        return Customer::where('guest','1')
            ->whereIn('guardian_person', $publicWhere)
            ->orWhere('guest','2')
            ->whereIn('guardian_person', $privateWhere)
            ->with('guardianPerson:guid,name', 'entryPerson:guid,name')
            ->withCount('visit')
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page ?? 10);
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
        ]);
    }

    // 更新客源
    public function updateCustomer(
        $customer,
        $request,
        $permission
    )
    {
        // 联系方式权限
        if ($permission['contact']) {
            $customer->customer_info = $request->customer_info;
        }

        // 客源等级权限
        if ($permission['level']) {
            $customer->level = $request->level;
        }

        // 其他信息权限
        if ($permission['other']) {
            $customer->guest = $request->guest;
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
        }

        if (!$customer->save()) return false;
        return true;
    }

    // 客源详情
    public function getCustomerInfo($guid)
    {
        $res = Customer::with('entryPerson:guid,name,tel', 'guardianPerson:guid,name,tel', 'track', 'track.user', 'visit')->where('guid', $guid)->first();
        $permission = array();
        $permission['public_change_private'] = true; // 是否有公客转私客权限
        $permission['private_change_public'] = true; // 是否有私客转公客权限
        $permission['customer_change_invalid'] = true; // 是否有转为无效权限
        $permission['entry_person'] = true; // 是否有修改录入人权限
        $permission['guardian_person'] = true; // 是否有修改维护人的权限
        $permission['visit_permission'] = true; // 是否允许带看
        $permission['edit_customer'] = true; // 是否允许编辑客源

        $publicChangePrivate = Access::adoptGuardianPersonGetCustomer('public_change_private');
        if (!in_array($guid,$publicChangePrivate)) $permission['public_change_private'] = false;

        $privateChangePublic = Access::adoptGuardianPersonGetCustomer('private_change_public');
        if (!in_array($guid,$privateChangePublic)) $permission['private_change_public'] = false;

        $customerChangeInvalid = Access::adoptGuardianPersonGetCustomer('customer_change_invalid');
        if (!in_array($guid,$customerChangeInvalid)) $permission['customer_change_invalid'] = false;

        // 判断是否有修改录入人权限
        $entry_person = Access::adoptPermissionGetUser('set_customer_entry_person');
        if (!in_array($res->entry_person, $entry_person['message'])) $permission['entry_person'] = false;

        // 判断是否有修改维护人的权限
        $guardian_person = Access::adoptGuardianPersonGetCustomer('set_customer_guardian_person');
        if (!in_array($guid, $guardian_person)) $permission['guardian_person'] = false;

        // 是否允许带看
        $seeCustomerVisit = Access::adoptGuardianPersonGetCustomer('see_customer_visit');
        if (!in_array($guid, $seeCustomerVisit)) $permission['visit_permission'] = false;

        // 是否允许编辑客源
        $editCustomer = Access::adoptGuardianPersonGetCustomer('edit_customer');
        if (!in_array($guid,$editCustomer)) $permission['edit_customer'] = false;

        // 私客查看范围
        if ($res->guest == 2) {
            $privateCustomerShow = Access::adoptGuardianPersonGetCustomer('private_customer_show');
            if (!in_array($guid,$privateCustomerShow)) $permission['private_customer_show'] = false;
            $permission['private_customer_show'] = true; // 私客查看范围
        }

        $data = [];
        $data['permission'] = $permission;
        $data['guid'] = $res->guid;
        $data['level'] = $res->level_cn;
        $data['guest'] = $res->guest_cn;
        $data['title'] = $res->price_interval_cn . ',' . $res->acreage_interval_cn . '的写字楼。';
        $data['remarks'] = $res->remarks;
        $data['status'] = $res->status;
        $data['customer_name'] = $res->customer_info[0]['name'];
        $data['area'] = $res->intention_cn; // 意向区域
        $data['block'] = $res->block_cn; // 意向商圈
        $data['building'] = $res->building_cn; // 意向楼盘
        $data['house_type'] = $res->house_type_cn; // 户型
        $data['acreage'] = $res->acreage_interval_cn; // 面积
        $data['price'] = $res->price_interval_cn; // 价格
        $data['floor'] = $res->floor_cn; // 楼层
        $data['type'] = $res->type_cn; // 房屋类型
        $data['renovation'] = $res->renovation_cn;
        $data['entry_person'] = $res->entryPerson;  // 录入人信息
        $data['guardian_person'] = $res->guardianPerson; // 维护人
        $data['created_at'] = $res->created_at->format('Y-m-d H:i:s');
        $data['invalid_reason'] = $res->invalid_reason;

        if ($permission['visit_permission']) {
            $item = CustomerOperationRecord::where('customer_guid', $guid)
                ->whereIn('type', [1, 2])
                ->latest()
                ->take(4)
                ->pluck('type', 'created_at')
                ->toArray();
        } else {
            $item = CustomerOperationRecord::where('customer_guid', $guid)
                ->where('type', 1)
                ->latest()
                ->take(4)
                ->pluck('type', 'created_at')
                ->toArray();
        }

        // 获取动态(跟进,带看) 最新4条数据

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
        $data['dynamic'] = [];
        if (!empty($record)) {
            foreach ($record as $k => $v) {
                if (!empty($v)) {
                    $data['dynamic'][$k]['guid'] = $v->guid; // guid
                    $data['dynamic'][$k]['user_name'] = $v->user->name; // 跟进人/带看人
                    $data['dynamic'][$k]['remarks'] = $v->remarks ? $v->remarks : $v->tracks_info;
                    $data['dynamic'][$k]['title'] = $v->house ? Common::HouseTitle($v->house->guid) : null;
                    $data['dynamic'][$k]['created_at'] = $v->created_at->format('Y-m-d H:i:s');
                    // 是否允许编辑
                    $data['dynamic'][$k]['operation'] = false;
                    if (time() - strtotime($v->created_at->format('Y-m-d H:i')) <= 60 * 30) {
                        $guid = $v->user_guid ? $v->user_guid : $v->visit_user;
                        if ($guid == Common::user()->guid) {
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
            $status = '';
            if ($request->status == 3) {
                $status = '暂缓,';
            } elseif ($request->status == 4) {
                $status = '内成交,';
            } elseif ($request->status == 5) {
                $status = '外成交,';
            } elseif ($request->status == 6) {
                $status = '电话错误,';
            } elseif ($request->status == 7) {
                $status = '其他,';
            }

            $remarks = "将客源转为无效,原因是$status" . $request->invalid_reason;
            $data = ['status' => $request->status];
            if ($request->status != 1 && $request->status != 2) {
                $data['invalid_reason'] = $remarks;
            }
            $suc = Customer::where('guid', $request->guid)->update($data);
            if (!$suc) throw new \Exception('客源转为有效/无效失败');
            if ($request->status == 1) {
                $res = Common::customerOperationRecords(Common::user()->guid, $request->guid, 4, '转为有效');
            } else {
                $res = Common::customerOperationRecords(Common::user()->guid, $request->guid, 4, $remarks);
            }

            if (!$res) throw new \Exception('客源操作记录添加失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('客源设置失败' . $exception->getMessage());
            return false;
        }
    }

    // 更改客源类型(公私客)
    public function updateGuest($request)
    {
        \DB::beginTransaction();
        try {
            $suc = Customer::where('guid', $request->guid)->update(['guest' => $request->guest]);
            if (!$suc) throw new \Exception('房源类型更改失败');

            if ($request->guest == 2) {
                $remarks = '公客转为私客';
            } else {
                $remarks = '私客转为公客';
            }
            $res = Common::customerOperationRecords(Common::user()->guid, $request->guid, 4, $remarks);
            if (!$res) throw new \Exception('客源操作记录添加失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('房源类型更改失败' . $exception->getMessage());
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
        // 查出私人及公客
        $res = Customer::where([
            'company_guid' => Common::user()->company_guid,
            'guardian_person' => Common::user()->guid,
            'status' => 1,
        ])->orWhere('guest',1)
            ->get();

        return $res->map(function ($v) {
            return [
                'value' => $v->guid,
                'label' => '客户:' . $v->customer_info[0]['name'] . '  电话:' . $v->customer_info[0]['tel']
            ];
        });
    }

    // 获取客源信息
    public function getCustomersInfo($request)
    {
        \DB::beginTransaction();
        try {
            $customer = Customer::where(['guid' => $request->guid])->first();
            if (empty($customer)) throw new \Exception('获取客源信息失败');

            // 私客查看范围(判断是否有权限)
            if ($customer->guest == 2) {
                $privateCustomerShow = Access::adoptGuardianPersonGetCustomer('private_customer_show');
                if (!in_array($request->guid,$privateCustomerShow)) throw new \Exception('暂无权限');
            }

            $customerOperationRecords = Common::customerOperationRecords(Common::user()->guid, $request->guid, 3, '查看了客源联系方式');
            if (empty($customerOperationRecords)) throw new \Exception('查看客源信息添加操作记录失败');

            \DB::commit();
            return $customer->customer_info;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 获取客源动态
    public function getDynamic($request)
    {
        // 判断有无查看带看权限
        $customer = Access::adoptGuardianPersonGetCustomer('see_customer_visit');
        $permission = in_array($request->customer_guid, $customer);

        $res = CustomerOperationRecord::with('user:guid,name,tel')->where('customer_guid', $request->customer_guid);

        // 如果没有权限, 则全部里面不显示带看信息
        if (!$permission) $res = $res->where('type', '!=', 2);

        // 如果查看带看
        if (!empty($request->type) && $request->type == 2 && !$permission) {
            return ['status' => false, 'message' => '无权限查看该客源带看'];
        } elseif (!empty($request->type)) {
            $res = $res->where('type', $request->type);
        }
        $res = $res->latest()->get();

        if (empty($res)) return [];
        foreach ($res as $v) {
            // 如果是带看  查询房子的相关信息
            if ($v->type == 2) {
                // 查询带看的房源
                $house_guid = Visit::where([
                    'cover_rel_guid' => $v->customer_guid,
                    'model_type' => 'App\Models\Customer',
                    'created_at' => $v->created_at->format('Y-m-d H:i:s')
                ])->value('rel_guid');

                if (empty($house_guid)) {
                    $v['house'] = '';
                } else {
                    $v['house'] = Common::HouseTitle($house_guid);
                }
            }

            if ($v->type = 1) {
                $v->operation = false;
                if (time() - strtotime($v->created_at->format('Y-m-d H:i')) <= 60 * 30) {
                    if ($v->user_guid == Common::user()->guid) {
                        $v->operation = true;
                    }
                }
            }
            $v->remarks = $v->remarks??'';
        }
        return ['status' => true, 'message' => $res];
    }

    // 获取修改相关权限
    public function getPermission($customer)
    {
        $data = [];
        $data['contact'] = true;
        $data['level'] = true;
        $data['other'] = true;

        // 判断是否有修改联系方式权限
        $contact = Access::adoptGuardianPersonGetCustomer('customer_contact_way');
        if (!in_array($customer->guid, $contact)) $data['contact'] = false;

        // 判断是否有修改客源等级权限
        $level = Access::adoptGuardianPersonGetCustomer('update_customer_grade');
        if (!in_array($customer->guid,$level)) $data['level'] = false;

        // 判断是否有修改其他信息权限
        $other = Access::adoptGuardianPersonGetCustomer('update_customer_other');
        if (!in_array($customer->guid, $other))  $data['other'] = false;

        return $data;
    }
}