<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class BuildingBlock extends Model
{
    protected $table = 'building_blocks';

    protected $guarded = [];

    protected $connection = 'buildings';

    public function building()
    {
        return $this->belongsTo('App\Models\Building','building_guid','guid');
    }
}
