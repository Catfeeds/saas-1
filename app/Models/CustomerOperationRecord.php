<?php

namespace App\Models;


class CustomerOperationRecord extends BaseModel
{
    // 用户
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_guid','guid');
    }

    public function visit()
    {
        return $this->hasOne(Visit::class,'cover_rel_guid', 'customer_guid')->where('model_type', 'App\Models\Customer');
    }

}
