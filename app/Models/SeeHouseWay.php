<?php

namespace App\Models;

class SeeHouseWay extends BaseModel
{
    // 关联门店
    public function storefront()
    {
        return $this->belongsTo(CompanyFramework::class,'storefront_guid', 'guid');
    }
}
