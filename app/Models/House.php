<?php

namespace App\Models;


class House extends BaseModel
{
    protected $casts = [
        'owner_info' => 'array',
        'cost_detail' => 'array',
        'support_facilities' => 'array',
        'indoor_img' => 'array',
        'house_type_img' => 'array',
        'outdoor_img' => 'array',
        'relevant_proves_img' => 'array'
    ];

    protected $appends = [
        'acreage_cn',
        'public_private_cn',
        'grade_cn',
        'payment_type_cn',
        'renovation_cn',
        'orientation_cn',
        'type_cn',
        'indoor_img_cn',
        'source_cn',
        'split_cn',
        'register_company_cn',
        'open_bill_cn',
        'shortest_lease_cn',
        'actuality_cn',
        'relevant_proves_img_cn',
        'indoor_img_url',
        'house_type_img_url',
        'outdoor_img_url',
        'status_cn',
        'lower_cn'
    ];

    // 房源关联钥匙
    public function key()
    {
        return $this->hasOne(SeeHouseWay::class,'house_guid', 'guid');
    }
    
    // 楼座
    public function buildingBlock()
    {
        return $this->belongsTo('App\Models\BuildingBlock','building_block_guid','guid');
    }

    // 跟进
    public function track()
    {
        return $this->hasMany(Track::class, 'rel_guid', 'guid')->where('model_type', 'App\Models\House')->orderBy('created_at','desc');
    }
    
    // 动态
    public function record()
    {
        return $this->hasManyThrough(User::class,HouseOperationRecord::class,'house_guid', 'guid', 'guid', 'user_guid');
    }
    
    // 录入人
    public function entryPerson()
    {
        return $this->hasOne(User::class, 'guid', 'entry_person');
    }

    // 维护人
    public function guardianPerson()
    {
        return $this->hasOne(User::class, 'guid', 'guardian_person');
    }

    // 图片人
    public function picPerson()
    {
        return $this->hasOne(User::class, 'guid', 'pic_person');
    }

    // 钥匙人
    public function keyPerson()
    {
        return $this->hasOne(User::class, 'guid', 'key_person');
    }

    // 看房方式
    public function seeHouseWay()
    {
        return $this->belongsTo('App\Models\SeeHouseWay','guid','house_guid');
    }

    // 关联共享记录
    public function shareRecord()
    {
        return $this->hasMany(HouseShareRecord::class, 'house_guid', 'guid')->orderBy('created_at', 'desc');
    }

    // 房子关联公司
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_guid', 'guid');
    }

    // 面积   acreage_cn
    public function getAcreageCnAttribute()
    {
        if (empty($this->acreage)) return '暂无面积';
        return $this->acreage.'㎡';
    }

    // 室内图
    public function getIndoorImgCnAttribute()
    {
        return $this->indoor_img?config('setting.qiniu_url').$this->indoor_img[0]:config('setting.pc_building_house_default_img');
    }

    // 公私盘中文
    public function getPublicPrivateCnAttribute()
    {
        switch ($this->public_private) {
            case 1:
                return '私盘';
                break;
            case 2:
                return '公盘';
                break;
                default;
                break;
        }
    }

    // 房源等级中文
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

    // 付款方式中文
    public function getPaymentTypeCnAttribute()
    {
        switch ($this->payment_type) {
            case 1:
                return '押一付一';
                break;
            case 2:
                return '押一付二';
                break;
            case 3:
                return '押一付三';
                break;
            case 4:
                return '押二付一';
                break;
            case 5:
                return '押二付二';
                break;
            case 6:
                return '押二付三';
                break;
            case 7:
                return '押三付一';
                break;
            case 8:
                return '押三付二';
                break;
            case 9:
                return '押三付三';
                break;
            case 10:
                return '半年付';
                break;
            case 11:
                return '年付';
                break;
            case 12:
                return '面谈';
                break;
                default;
                break;
        }
    }

    // 装修程度
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

    // 朝向中文
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

    // 写字楼类型中文
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

    // 现状 actuality_cn
    public function getActualityCnAttribute()
    {
        if ($this->actuality == 1) {
            return '空置';
        } elseif ($this->actuality == 2) {
            return '自用';
        } elseif ($this->actuality == 3) {
            return '在租';
        } else {
            return '暂无';
        }
    }

    // 最短租期 shortest_lease_cn
    public function getShortestLeaseCnAttribute()
    {
        if ($this->shortest_lease == 1) {
            return '1-2年';
        } elseif ($this->shortest_lease == 2) {
            return '2-3年';
        } elseif ($this->shortest_lease == 3) {
            return '3-4年';
        } elseif ($this->shortest_lease == 4) {
            return '5年以上';
        } else {
            return '暂无';
        }
    }

    // 可开发票 open_bill_cn
    public function getOpenBillCnAttribute()
    {
        if ($this->open_bill == 1) {
            return '可开发票';
        } elseif ($this->open_bill == 2) {
            return '不可开发票';
        } else {
            return '暂无';
        }
    }

    // 注册公司 register_company_cn
    public function getRegisterCompanyCnAttribute()
    {
        if ($this->register_company == 1) {
            return '可以';
        } elseif ($this->register_company == 2) {
            return '不可以';
        } else {
            return '暂无';
        }
    }

    // 是否可以拆分 split_cn
    public function getSplitCnAttribute()
    {
        if ($this->split == 1) {
            return '可拆分';
        } elseif ($this->split == 2) {
            return '不可拆分';
        } else {
            return '暂无';
        }
    }

    // 来源渠道 source_cn
    public function getSourceCnAttribute()
    {
        if ($this->source == 1) {
            return '上门';
        } elseif ($this->source == 2) {
            return '电话';
        } elseif ($this->source == 3) {
            return '洗盘';
        } elseif ($this->source == 4) {
            return '网络';
        } elseif ($this->source == 5) {
            return '陌拜';
        } elseif ($this->source == 6) {
            return '转介绍';
        } elseif ($this->source == 7) {
            return '老客户';
        } else {
            return '暂无';
        }
    }

    // 相关证件图片 relevant_proves_img_cn
    public function getRelevantProvesImgCnAttribute()
    {
        return collect($this->relevant_proves_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img,
            ];
        })->values();
    }

    // 室内图  indoor_img_url
    public function getIndoorImgUrlAttribute()
    {
        return collect($this->indoor_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img,
            ];
        })->values();
    }

    // 户型图 house_type_img_url
    public function getHouseTypeImgUrlAttribute()
    {
        return collect($this->house_type_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img,
            ];
        })->values();
    }

    // 室外图 outdoor_img_url
    public function getOutdoorImgUrlAttribute()
    {
        return collect($this->outdoor_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img,
            ];
        })->values();
    }

    // 房源状态 status_cn
    public function getStatusCnAttribute()
    {
        if ($this->status == 1) {
            return '有效';
        } elseif ($this->status == 2) {
            return '无效';
        } elseif ($this->status == 3) {
            return '无效-暂缓';
        } elseif ($this->status == 4) {
            return '无效-内成交';
        } elseif ($this->status == 5) {
            return '无效-外成交';
        } elseif ($this->status == 6) {
            return '无效-信息有误';
        } elseif ($this->status == 7) {
            return '无效-其他';
        }
    }




    // 下架中文 lower_cn
    public function getLowerCnAttribute()
    {
        switch ($this->lower_frame) {
            case 1:
                return '平台下架';
                break;
            case 2:
                return '自主下架';
                break;
                default;
                break;
        }
    }
    
    
}
