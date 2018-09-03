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
        'price' => 'array',
        'acreage' => 'array',
        'floor' => 'array'
    ];

    protected $appends = [
        'level_cn', 'type_cn', 'renovation_cn'
    ];

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

    // 录入人
    public function entryPerson()
    {
        return $this->belongsTo(User::class, 'guid', 'entry_person');
    }
    
    // 维护人 
    public function guardianPerson()
    {
        return $this->belongsTo(User::class, 'guid', 'guardian_person');
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

}
