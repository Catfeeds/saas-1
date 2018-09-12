<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaBuildingBlock extends Model
{
    use SoftDeletes;

    protected $connection = 'media';

    protected $table = 'building_blocks';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    protected $appends = ['info', 'block_info'];

    /**
     * 说明：所属楼盘
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function building()
    {
        return $this->belongsTo(MediaBuilding::class);
    }

    public function office()
    {
        return $this->hasMany(OfficeBuildingHouse::class);
    }



    /**
     * 说明：获取楼座info
     *
     * @return string
     * @author jacklin'
     */
    public function getInfoAttribute()
    {
        $building = $this->building;
        if (empty($building)) return;
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $building->name . $blocksInfo;
    }

    /**
     * 说明：获取楼座info
     *
     * @return string
     * @author jacklin'
     */
    public function getBlockInfoAttribute()
    {
        $blocksInfo = $this->name . $this->name_unit;
        if (!empty($this->unit)) $blocksInfo = $blocksInfo . $this->unit . $this->unit_unit;
        return $blocksInfo;
    }
}
