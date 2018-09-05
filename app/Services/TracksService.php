<?php

namespace App\Services;

use App\Handler\Common;
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
                $houseOperationRecords = Common::houseOperationRecords(Common::user()->guid, $request->rel_guid, 1, $request->tracks_info);
                if (empty($houseOperationRecords)) throw new \Exception('房源跟进操作记录添加失败');
            } elseif ($request->model_type == 'App\Models\Customer') {
                $customerOperationRecords = Common::customerOperationRecords(Common::user()->guid, $request->rel_guid,1, $request->tracks_info);
                if (empty($customerOperationRecords)) throw new \Exception('客源带看操作记录失败');
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
            $houseOperationRecord = HouseOperationRecord::where([
                'house_guid' => $track->rel_guid,
                'created_at' => $track->created_at
            ])->update(['remarks' => $request->tracks_info]);
            if (empty($houseOperationRecord)) throw new \Exception('房源跟进记录修改失败');
            \DB::commit();
            return $track;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }
    }

}