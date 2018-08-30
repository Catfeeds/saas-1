<?php

namespace App\Repositories;

use App\Handler\Common;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Model;

class VisitsRepository extends Model
{
    //获取房源或者客源带看列表
    public function visitsList($request, $service)
    {
        $res = Visit::with('user', 'accompanyUser', 'house')->where('cover_rel_guid', $request->cover_rel_guid)->latest()->paginate($request->per_page??10);
        $visit = [];
        foreach ($res as $key =>  $v) {
            $visit[$key] = $service->getData($v);
        }
        return $res->setCollection(collect($visit));
    }

    //添加房源客源带看
    public function addVisit($request)
    {
        return Visit::create([
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
    }

}