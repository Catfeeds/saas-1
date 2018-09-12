<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\Customer;
use App\Models\CustomerOperationRecord;
use App\Models\Visit;

class VisitsService
{
    //房源或客源跟进列表
    public function getData($v)
    {
        $data = [];
        //如果是房源
        $data['guid'] = $v->guid;
        $data['visit_user'] = $v->user->name;
        $data['accompany'] = $v->accompany ? $v->accompanyUser->name : '';
        $data['remarks'] = $v->remarks;
        $data['time'] = $v->visit_date;
        $data['visit_img'] = $v->visit_img_cn;
        if ($v->model_type == 'App\Models\Customer') {
            $data['house_guid'] = $v->house->guid;
            $data['house_title'] = $v->house->title;
            $data['house_img'] = $v->house->indoor_img_cn;
        }
        return $data;
    }

    //添加房源客源带看
    public function addVisit($request)
    {
        \DB::beginTransaction();
        try {
            $visit = Visit::create([
                'guid' => Common::getUuid(),
                'visit_user' => Common::user()->guid,
                'accompany' => $request->accompany,
                'model_type' => $request->model_type,
                'cover_rel_guid' => $request->cover_rel_guid,
                'rel_guid' => $request->rel_guid,
                'remarks' => $request->remarks,
                'visit_img' => $request->visit_img,
                'visit_date' => $request->visit_date,
                'visit_time' => $request->visit_time,
            ]);
            if (empty($visit)) throw new \Exception('房源/客源带看失败');


            // 添加操作记录
            if ($request->model_type == 'App\Models\House') {
                $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->cover_rel_guid, 2, $request->remarks, json_encode($request->visit_img),null, $visit->guid);
                if (empty($houseOperationRecords)) throw new \Exception('房源/客源带看操作记录添加失败');
            }

            if ($request->model_type == 'App\Models\Customer') {
                $customerOperationRecords = Common::customerOperationRecords(Common::user()->guid, $request->cover_rel_guid, 2, $request->remarks, $visit->guid);
                if (empty($customerOperationRecords)) throw new \Exception('客源带看操作记录添加失败');
            }

            \DB::commit();
            return $visit;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 修改
    public function updateVisit($request, $visit)
    {
        \DB::beginTransaction();
        try {
            $oldRemarks = $visit->remarks;
            $visit->remarks = $request->remarks;
            if (!$visit->save()) throw new \Exception('带看修改失败');
            // 修改操作记录
            $suc = CustomerOperationRecord::where([
                'remarks' => $oldRemarks,
                'customer_guid' => $visit->cover_rel_guid,
                'created_at' => $visit->created_at
            ])->update(['remarks' => $request->remarks]);
            if (!$suc) throw new \Exception('带看修改失败');
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('带看修改失败'.$exception->getMessage());
            return false;
        }
    }

    // 删除带看、对应的操作记录
    public function delVisit($visit)
    {
        \DB::beginTransaction();
        try {
            // 判断权限
            $customer = Access::adoptGuardianPersonGetCustomer('del_customer_visit');
            if (!in_array($visit->cover_rel_guid, $customer)) return ['status' => false, 'message' => '无权限删除该客源带看'];

            // 删除对应的操作记录
            $suc = CustomerOperationRecord::where(['track_guid' => $visit->guid, 'type' =>2 ])->delete();
            if (!$suc) return ['status' => false , 'message' => '操作记录删除失败'];

            // 删除带看
            $res = $visit->delete();
            if (!$res) return ['status' => false, 'message' => '带看删除失败'];
            \DB::commit();
            return ['status' => true, 'message' => '带看删除成功'];
        } catch(\Exception $exception) {
            \DB::rollback();
            \Log::error('带看删除失败'.$exception->getMessage());
            return ['status' => false, 'message' => '带看删除失败'];
        }
    }


}