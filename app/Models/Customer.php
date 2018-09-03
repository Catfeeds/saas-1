<?php

namespace App\Models;


class Customer extends BaseModel
{
    protected $casts = [
        'customer_info' => 'array',
        'intention' => 'array',
        'block' => 'array',
        'building' => 'array',
        'house_type' => 'array',
    ];

    protected $appends = [
        'level_cn', 'type_cn', 'renovation_cn', 'acreage_cn', 'price_cn', 'floor_cn', 'guest_cn'
    ];

    // 录入人
    public function entryPerson()
    {
        return $this->belongsTo(User::class, 'entry_person', 'guid');
    }

    // 维护人
    public function guardianPerson()
    {
        return $this->belongsTo(User::class, 'guardian_person', 'guid');
    }

    // 跟进
    public function track()
    {
        return $this->hasMany(Track::class,'rel_guid', 'guid')->where('model_type', 'App\Models\Customer');
    }

    // 带看
    public function remind()
    {
        return $this->hasMany(Visit::class,'cover_rel_guid', 'guid')->where('model_type', 'App\Models\Customer');
    }

    //等级中文
    public function getLevelCnAttribute()
    {
        switch ($this->level) {
            case 1:
                return 'A';
                break;
            case 2:
                return 'B';
                break;
            case 3:
                return 'C';
                break;
                default;
                break;
        }
    }

    //房源类型中文
    public function getTypeCnAttribute()
    {
        switch ($this->type) {
            case 1:
                return '纯写字楼';
                break;
            case 2:
                return '商住楼';
                break;
            case 3:
                return '商业综合体';
                break;
            case 4:
                return '酒店写字楼';
                break;
            case 5:
                return '其他';
                break;
                default;
                break;
        }
    }

    //公私盘
    public function getGuestCnAttribute()
    {
        switch ($this->guest) {
            case 1:
                return '公';
                break;
            case 2:
                return '私';
                default;
                break;
        }
    }

    // 装修中文
    public function getRenovationCnAttribute()
    {
        switch ($this->renovation) {
            case 1:
                return '豪华装修';
                break;
            case 2:
                return '精装修';
                break;
            case 3:
                return '中装修';
                break;
            case 4:
                return '简装修';
                break;
            case 5:
                return '毛坯';
                break;
            default;
                break;
        }
    }
    
    // 面积中文
    public function getAcreageCnAttribute()
    {
        if ($this->min_acreage && $this->max_acreage) {
            return $this->min_acreage . '-' . $this->max_acreage .' ㎡';
        } elseif ($this->min_acreage && !$this->max_acreage) {
            return $this->min_acreage. ' ㎡以上';
        } elseif (!$this->min_acreage && $this->max_acreage) {
            return $this->max_acreage. ' ㎡以下';
        } else {
            return '';
        }
    }

    // 价格中文
    public function getPriceCnAttribute()
    {
        if ($this->min_price && $this->max_price) {
            return $this->min_price . '-' . $this->max_price .' 元/月';
        } elseif ($this->min_price && !$this->max_price) {
            return $this->min_price. ' 元/月以上';
        } elseif (!$this->min_price && $this->max_price) {
            return $this->max_price. ' 元/月以下';
        } else {
            return '';
        }
    }

    // 楼层中文
    public function getFloorCnAttribute()
    {
        if ($this->min_floor && $this->max_floor) {
            return $this->min_floor . '-' . $this->max_floor .'层';
        } elseif ($this->min_floor && !$this->max_floor) {
            return $this->min_floor. '层以上';
        } elseif (!$this->min_floor && $this->max_floor) {
            return $this->max_floor. '层以下';
        } else {
            return '';
        }
    }



}
