<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Remind;
use Illuminate\Database\Eloquent\Model;

class RemindsRepository extends Model
{
    // 写提醒
    public function writeReminder($request)
    {
        return Remind::create([
            'guid' => Common::getUuid(),
            'remind_info' => $request->remind_info,
            'user_guid' => Common::user()->guid,
            'model_type' => $request->model_type,
            'rel_guid' => $request->rel_guid,
            'remind_time' => $request->remind_time,
        ]);
    }
}