<?php

namespace App\Models;


class House extends BaseModel
{
    protected $casts = [
        'owner_info' => 'array',
        'indoor_img' => 'array'
    ];

    protected $appends = [
        'price_unit_cn',
        'acreage_cn',
        'public_private_cn',
        'grade_cn',
        'payment_type_cn',
        'renovation_cn',
        'orientation_cn',
        'type_cn',
        'indoor_img_cn'
    ];

    //房源关联钥匙
    public function key()
    {
        return $this->hasOne(SeeHouseWay::class,'house_guid', 'guid');
    }
    
    // 楼座
    public function buildingBlock()
    {
        return $this->belongsTo('App\Models\BuildingBlock','building_block_guid','guid');
    }

    public function tracks()
    {
        return $this->hasMany(Track::class, 'rel_guid', 'guid');
    }

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


    // 室内图
    public function getIndoorImgCnAttribute()
    {
        return $this->indoor_img?config('setting.qiniu_url').$this->indoor_img[0]['img']. config('setting.qiniu_suffix'):config('setting.pc_building_house_default_img');
    }

    //公私盘中文
    public function getPublicPrivateCnAttribute()
    {
        switch ($this->public_private) {
            case 1:
                return '私';
                break;
            case 2:
                return '公';
                break;
                default;
                break;
        }
    }

    //房源等级中文
    public function getGradeCnAttribute()
    {
        switch ($this->grade) {
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

    //付款方式中文
    public function getPaymentTypeCnAttribute()
    {
        switch ($this->payment_type) {
            case 1:
                return '押一付三';
                break;
            case 2:
                return '押一付二';
                break;
            case 3:
                return '押一付一';
                break;
            case 4:
                return '押二付一';
                break;
            case 5:
                return '押三付一';
                break;
            case 6:
                return '半年付';
                break;
            case 7:
                return '年付';
                break;
            case 8:
                return '面谈';
                break;
                default;
                break;
        }
    }

    //装修程度
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
                return '间装修';
                break;
            case 5:
                return '毛坯';
                break;
                default;
                break;

        }
    }

    //朝向中文
    public function getOrientationCnAttribute()
    {
        switch ($this->orientation) {
            case 1:
                return '东';
                break;
            case 2:
                return '西';
                break;
            case 3:
                return '南';
                break;
            case 4:
                return '北';
                break;
            case 5:
                return '东南';
                break;
            case 6:
                return '东北';
                break;
            case 7:
                return '西南';
                break;
            case 8:
                return '西北';
                break;
            case 9:
                return '东西';
                break;
            case 10:
                return '南北';
                break;
                default;
                break;
        }
    }

    //写字楼类型中文
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
                return '商业综合体楼';
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
}
