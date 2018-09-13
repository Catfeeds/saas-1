<?php

namespace App\Models;


class Company extends BaseModel
{
    protected $table = 'companies';

    // 用户
    public function user()
    {
        return $this->hasOne('App\Models\User','company_guid','guid');
    }
}
