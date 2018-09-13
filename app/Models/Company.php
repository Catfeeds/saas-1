<?php

namespace App\Models;


class Company extends BaseModel
{
    protected $table = 'companies';

    // 用户
    public function user()
    {
        return $this->HasMany('App\Models\User','company_guid','guid');
    }

    // 城市
    public function city()
    {
        return $this->belongsTo('App\Models\City','city_guid','guid');
    }
}
