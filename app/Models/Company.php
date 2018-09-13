<?php

namespace App\Models;


class Company extends BaseModel
{
    protected $table = 'companies';

    protected $appends = [
        'city_name_cn',
        'user_name_cn'
    ];
    // 用户
    public function user()
    {
        return $this->hasOne('App\Models\User','company_guid','guid');
    }

    // 城市
    public function city()
    {
        return $this->belongsTo('App\Models\City','guid','city_guid');
    }

    // 区域
    public function area()
    {
        return $this->belongsTo('App\Models\Area','guid','area_guid');
    }

    // 城市名
    public function getCityNameCnAttribute()
    {
        return City::where('guid',$this->city_guid)->value('name');
    }
   
}
