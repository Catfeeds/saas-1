<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Track;
use Illuminate\Database\Eloquent\Model;

class TracksRepository extends Model
{
    // 写跟进
    public function addTrack($rquest)
    {
        return Track::create([
           'guid' => Common::getUuid(),
           'model_type' => $rquest->model_type,
            'rel_guid' => $rquest->rel_guid,
            'tracks_info' => $rquest->tracks_info,
        ]);
    }
}