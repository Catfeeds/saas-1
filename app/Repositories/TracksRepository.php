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
        return Track::create([
            'guid' => Common::getUuid(),
            'model_type' => $request->model_type,
            'rel_guid' => $request->rel_guid,
            'tracks_info' => $request->tracks_info,
        ]);
    }
}