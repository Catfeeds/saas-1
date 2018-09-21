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
        return $this->belongsTo(House::class,'guid', 'rel_guid');
    }

    // 关联客源
    public function customer()
    {
        return $this->belongsTo(Customer::class,'guid','rel_guid');
    }
}
