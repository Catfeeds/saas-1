<?php

namespace App\Models;

class CompanyFramework extends BaseModel
{
    protected $table = 'company_frameworks';
    //自关联
    public function framework()
    {
        return $this->hasMany(CompanyFramework::class, 'parent_guid','guid');
    }

    //关联人员
    public function users()
    {
        return $this->hasMany(User::class, 'rel_guid', 'guid');
    }
}
