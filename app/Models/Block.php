<?php

namespace App\Models;

class Block extends BaseModel
{
    protected $table = 'blocks';

    protected $guarded = [];

    protected $connection = 'buildings';

    public function area()
    {
        return $this->belongsTo('App\Models\Area','area_guid','guid');
    }

    public function building()
    {
        return $this->hasMany('App\Models\Building','block_guid', 'guid');
    }
}
