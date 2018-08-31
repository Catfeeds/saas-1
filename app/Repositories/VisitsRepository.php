<?php

namespace App\Repositories;

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
}