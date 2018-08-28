<?php

namespace App\Models;

class Area extends BaseModel
{
    protected $table = 'areas';

    protected $guarded = [];

    protected $connection = 'buildings';
    // 城市
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // 区域管理商圈
    public function block()
    {
        return $this->hasMany('App\Models\Block','area_guid','guid');
    }

    // 楼盘
    public function building()
    {
        return $this->hasMany(Building::class, 'area_guid', 'guid');
    }

}
