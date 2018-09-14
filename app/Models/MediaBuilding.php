<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MediaBuilding extends Model
{
    use SoftDeletes;

    protected $connection = 'media';

    protected $table = 'buildings';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    protected $casts = [
        'company' => 'array',
        'gps' => 'array',
        'album' => 'array',
        'years' => 'string'
    ];

    protected $appends = [
        'type_label', 'area_label', 'block_label', 'blocks_count', 'city_id', 'city_label', 'album_img'
    ];

    /**
     * 说明：楼盘下的所有楼座
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * @author jacklin
     */
    public function buildingBlocks()
    {
        return $this->hasMany('App\Models\BuildingBlock');
    }

    /**
     * 说明：所属商圈
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function block()
    {
        return $this->belongsTo('App\Models\Block');
    }

    /**
     * 说明：所属街道
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author jacklin
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area');
    }

    // 楼盘关联房源
    public function house()
    {
        return $this->hasManyThrough(OfficeBuildingHouse::class,BuildingBlock::class);
    }

    /**
     * 说明：楼盘类型信息
     *
     * @return string
     * @author jacklin
     */
    public function getTypeLabelAttribute()
    {
        switch ($this->type) {
            case 1:
                return '住宅';
            case 2:
                return '写字楼';
            case 3:
                return '商铺';
        }
    }

    /**
     * 说明：区域信息
     *
     * @return string
     * @author jacklin
     */
    public function getAreaLabelAttribute()
    {
        $area = $this->area;
        if (empty($area)) return;
        return $area->name;
    }

    /**
     * 说明：商圈信息
     *
     * @return mixed
     * @author jacklin
     */
    public function getBlockLabelAttribute()
    {
        $block = $this->block;
        if (!empty($block)) return $block->name;
    }

    /**
     * 说明：楼座总数
     *
     * @return int
     * @author jacklin
     */
    public function getBlocksCountAttribute()
    {
        return $this->buildingBlocks()->count();
    }

    /**
     * 说明：城市id
     *
     * @return mixed
     * @author jacklin
     */
    public function getCityIdAttribute()
    {
        if (empty($this->area)) return;
        return $this->area->city->id;
    }

    /**
     * 说明：城市信息
     *
     * @return mixed
     * @author jacklin
     */
    public function getCityLabelAttribute()
    {
        if (empty($this->area)) return;

        return $this->area->city->name;
    }

    /**
     * 说明: 户型图拼接url
     *
     * @return static
     * @use house_type_img_cn
     * @author 罗振
     */
    public function getAlbumImgAttribute()
    {
        return collect($this->album)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.static')
            ];
        })->values();
    }

}
