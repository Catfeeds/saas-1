<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Track;
use Illuminate\Database\Eloquent\Model;

class TracksRepository extends Model
{
    // 写跟进
    public function addTrack($request)
    {
        \DB::beginTransaction();
        try {
            dd(Common::user()->guid);

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

            \DB::commit();
            return true;
        } catch (\Exception $exception) {
            \DB::rollback();
            return false;
        }

    }
}