<?php

namespace App\Models;


class CustomerOperationRecord extends BaseModel
{
    // 用户
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_guid','guid');
    }
}
