<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $table = 'buildings';

    protected $connection = 'buildings';

    // 区域
    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_guid','guid');
    }

    // 楼座
    public function buildingBlock()
    {
        return $this->hasMany('App\Models\BuildingBlock','building_guid','guid');
    }

}
