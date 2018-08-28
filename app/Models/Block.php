<?php

namespace App\Models;

class Block extends BaseModel
{
    protected $table = 'blocks';

    protected $guarded = [];

    protected $connection = 'buildings';

    // 区域
    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_guid','guid');
    }

    // 楼盘
    public function building()
    {
        return $this->hasMany('App\Models\Building','block_guid', 'guid');
    }
}
