<?php

namespace App\Services;

use App\Handler\Common;
use App\Models\Customer;
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
                // 查询客户
                $customer = Customer::find($request->rel_guid);

                // 备注
                if ($request->remarks) {
                    $remarks = '带看信息备注:'.$request->remarks.';带看的客户:'. $customer->customer_info[0]['name'] .';陪看人:'.$visit->accompanyUser->name;
                } else {
                    $remarks = '带看的客户:'. $customer->customer_info[0]['name'] .';陪看人:'.$visit->accompanyUser->name;
                }

                $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->cover_rel_guid, 2, $remarks, $request->visit_img);
                if (empty($houseOperationRecords)) throw new \Exception('房源/客源带看操作记录添加失败');
            }
            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }


}