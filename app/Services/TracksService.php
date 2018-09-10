<?php

namespace App\Services;

use App\Handler\Access;
use App\Handler\Common;
use App\Models\CustomerOperationRecord;
use App\Models\HouseOperationRecord;
use App\Models\Track;

class TracksService
{
    // 写跟进
    public function addTrack($request)
    {
        \DB::beginTransaction();
        try {
            $track = Track::create([
                'guid' => Common::getUuid(),
                'user_guid' => Common::user()->guid,
                'model_type' => $request->model_type,
                'rel_guid' => $request->rel_guid,
                'tracks_info' => $request->tracks_info,
            ]);
            if (empty($track)) throw new \Exception('跟进添加失败');
            // 修改房源/客源跟进时间
            $update = $request->model_type::where('guid', $request->rel_guid)->update(['track_time' => $track->created_at->format('Y-m-d H:i')]);
            if (empty($update)) throw new \Exception('修改房源/客源跟进时间失败');

            // 写入跟进
            if ($request->model_type == 'App\Models\House') {
                $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->rel_guid,  1,$request->tracks_info,null,$track->guid);
                if (empty($houseOperationRecords)) throw new \Exception('房源跟进操作记录添加失败');
            } elseif ($request->model_type == 'App\Models\Customer') {
                $customerOperationRecords = Common::customerOperationRecords(Common::user()->guid,
                    $request->rel_guid, 1, $request->tracks_info, $track->guid);
                if (empty($customerOperationRecords)) throw new \Exception('客源跟进操作记录失败');
            }
            \DB::commit();
            return $track;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 修改跟进信息
    public function updateTrack($request, $track)
    {
        \DB::beginTransaction();
        try {
            $track->tracks_info = $request->tracks_info;
            if (!$track->save()) throw new \Exception('修改跟进失败');
            // 修改操作记录
            $operationRecord = $request->model_type::where('track_guid', $track->guid)->update(['remarks' => $request->tracks_info]);
            if (empty($operationRecord)) throw new \Exception('房源/客源跟进记录修改失败');
            \DB::commit();
            return $track;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

    // 删除房源/客源跟进
    public function delTrack($track, $request)
    {

        \DB::beginTransaction();
        try {
            // 如果是删除房源跟进,判断房源权限
            if ($request->model_type == 'App\Models\House') {
                $house = Access::adoptGuardianPersonGetHouse('del_track');
                if (!in_array($track->rel_guid, $house)) return ['status' => false, 'message' => '无权限删除该房源跟进'];

                // 删除对应的房源操作记录
                $res = HouseOperationRecord::where('track_guid', $track->guid)->delete();
                if (!$res) return ['status' => false, 'message' => '房源操作记录删除失败'];
            }

            // 如果删除客源跟进, 判断客源权限
            if ($request->model_type == 'App\Models\Customer') {
                $customer = Access::adoptGuardianPersonGetCustomer('customer_del_track');
                if (!in_array($track->rel_guid, $customer)) return ['status' => false, 'message' => '无权限删除该客源跟进'];

                // 删除对应的客源操作记录
                $res = CustomerOperationRecord::where('track_guid', $track->guid)->delete();
                if (!$res) return ['status' => false, 'message' => '客源操作记录删除失败'];
            }
            // 删除跟进
            $suc = $track->delete();
            if (!$suc) return ['status' => false, 'message' => '跟进删除失败'];

            \DB::commit();
            return ['status' => true, 'message' => '跟进删除成功'];
        } catch (\Exception $exception) {
            \DB::rollback();
            \Log::error('跟进删除失败'.$exception->getMessage());
            return ['status' => false, 'message' => '跟进删除失败'];
        }
    }

}