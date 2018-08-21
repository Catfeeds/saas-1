<?php

namespace App\Models;

class CompanyFramework extends BaseModel
{
    //自关联
    public function framework()
    {
        return $this->hasMany(CompanyFramework::class, 'parent_guid','guid');
    }
}
