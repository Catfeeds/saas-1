<?php

namespace App\Models;

class SeeHouseWay extends BaseModel
{
    protected $appends = [
        'type_cn'
    ];

    // 关联门店
    public function storefront()
    {
        return $this->belongsTo(CompanyFramework::class,'storefront_guid', 'guid');
    }
    
    // 看房方式中文 type_cn
    public function getTypeCnAttribute()
    {
        switch ($this->type) {
            case 1:
                return '请预约';
                break;
            case 2:
                return '直接看';
                break;
            case 3:
                return '借钥匙';
                break;
                default;
                break;
        }
    }
}
