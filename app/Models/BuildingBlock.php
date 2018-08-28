<?php
namespace App\Models;

class BuildingBlock extends BaseModel
{
    protected $table = 'building_blocks';

    protected $guarded = [];

    protected $connection = 'buildings';

    public function building()
    {
        return $this->belongsTo('App\Models\Building','building_guid','guid');
    }
}
