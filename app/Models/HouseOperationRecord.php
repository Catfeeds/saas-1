<?php

namespace App\Models;

class HouseOperationRecord extends BaseModel
{

    protected $casts = [
        'img' => 'array'
    ];

    // 用户
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_guid','guid');
    }

}
