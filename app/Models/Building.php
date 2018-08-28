<?php

namespace App\Models;

class Building extends BaseModel
{
    protected $table = 'buildings';

    protected $guarded = [];

    protected $connection = 'buildings';

    // 区域
    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_guid','guid');
    }

}
