<?php

namespace App\Models;


class House extends BaseModel
{
    protected $appends = [
        'price_unit_cn',
        'acreage_cn'
    ];

    // 价格单位   price_unit_cn
    public function getPriceUnitCnAttribute()
    {
        if ($this->price_unit == 1) {
            return '元/月';
        } elseif ($this->price_unit == 2) {
            return '元/平/月';
        } elseif ($this->price_unit == 3) {
            return '元/平/天';
        }
    }

    // 面积   acreage_cn
    public function getAcreageCnAttribute()
    {
        if (empty($this->acreage)) return '';
        return $this->acreage.'㎡';
    }

    public function getIndoorimgCnAttribute()
    {
        return $this->indoor_img?config('setting.qiniu_url').$this->indoor_img[0]['img']. config('setting.qiniu_suffix'):config('setting.pc_building_house_default_img');
    }
}
