<?php
namespace App\Models;

class BuildingBlock extends BaseModel
{
    protected $table = 'building_blocks';

    protected $guarded = [];

    protected $connection = 'buildings';

    protected $appends = [
        'property_fee_cn'
    ];

    public function building()
    {
        return $this->belongsTo('App\Models\Building','building_guid','guid');
    }

    // 物业费 property_fee_cn
    public function getPropertyFeeCnAttribute()
    {
        if (empty($this->property_fee)) return '暂无';
        return $this->property_fee.'元/㎡';
    }
    
}
