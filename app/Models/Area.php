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
        return $this->belongsTo('App\Models\City','guid','city_guid');
    }

    // 区域管理商圈
    public function block()
    {
        return $this->hasMany('App\Models\Block','area_guid','guid');
    }

    // 楼盘
    public function building()
    {
        return $this->hasMany('App\Models\Building', 'area_guid', 'guid');
    }

}
