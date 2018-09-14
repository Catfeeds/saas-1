<?php

namespace App\Models;


class Company extends BaseModel
{
    // 城市
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_guid','guid');
    }
}
