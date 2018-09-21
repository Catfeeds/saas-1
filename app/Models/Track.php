<?php

namespace App\Models;


class Track extends BaseModel
{
    public function user()
    {
        return $this->hasOne(User::class, 'guid', 'user_guid');
    }

    // 关联房源
    public function house()
    {
        return $this->belongsTo(House::class,'rel_guid', 'guid');
    }

    // 关联客源
    public function customer()
    {
        return $this->belongsTo(Customer::class,'rel_guid','guid');
    }
}
