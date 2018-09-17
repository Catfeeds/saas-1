<?php

namespace App\Models;


class Company extends BaseModel
{
    // 城市
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_guid','guid');
    }

    // 区域
    public function area()
    {
        return $this->belongsTo('App\Models\City','area_guid','guid');

    }
}
