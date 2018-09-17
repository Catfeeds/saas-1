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
        'floor_cn',
        'guest_cn',
        'level_cn',
        'type_cn',
        'renovation_cn',
        'remarks_cn',
        'price_interval_cn',
        'acreage_interval_cn',
        'intention_cn',
        'house_type_cn',
        'intention_cn',
        'block_cn',
        'building_cn',
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
    public function visit()
    {
        return $this->hasMany(Visit::class,'cover_rel_guid', 'guid')->where('model_type', 'App\Models\Customer');
    }

    // 等级中文
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
            default:
                return '';
                break;
        }
    }

    // 房源类型中文
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
            default :
                return '-';
                break;
        }
    }

    // 公私盘
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

    // 备注
    public function getRemarksCnAttribute()
    {
        if (empty($this->remarks)) return '无备注';
        return $this->remarks;
    }

    // 价格区间
    public function getPriceIntervalCnAttribute()
    {
        if (empty($this->min_price) && (!empty($this->max_price))) {
            return $this->max_price . '元/月以下';
        } elseif ((!empty($this->min_price)) && empty($this->max_price)) {
            return $this->min_price . '元/月以上';
        } elseif (empty($this->min_price) && empty($this->max_price)) {
            return '暂无报价';
        }else {
            return $this->min_price .'-'. $this->max_price . '元/月';
        }
    }

    // 面积区间
    public function getAcreageIntervalCnAttribute()
    {
        if (empty($this->min_acreage) && (!empty($this->max_acreage))) {
            return $this->max_acreage . '㎡以下';
        } elseif ((!empty($this->min_acreage)) && empty($this->max_acreage)) {
            return $this->min_acreage . '㎡以上';
        } elseif (empty($this->min_acreage) && empty($this->max_acreage)) {
            return '不限面积';
        }else {
            return $this->min_acreage . '-' . $this->max_acreage . '㎡';
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
            return '不限楼层';
        }
    }

    // 户型 house_type_cn
    public function getHouseTypeCnAttribute()
    {
        return empty($this->house_type)?'不限户型':implode(',', $this->house_type);
    }

    // 区域 intention_cn
    public function getIntentionCnAttribute()
    {
        return empty($this->intention)?'':implode(',',$this->intention);
    }

    // 商圈 block_cn
    public function getBlockCnAttribute()
    {
        $blockName = array();
        if ($this->block) {
            foreach ($this->block as $v) {
                $blockName[] = $v['name'];
            }
        }

        return empty($blockName)?'':implode(',',$blockName);
    }

    // 楼盘 building_cn
    public function getBuildingCnAttribute()
    {
        $buildingName = array();
        if ($this->building) {
            foreach ($this->building as $v) {
                $buildingName[] = $v['name'];
            }
        }

        return empty($buildingName)?'':implode(',',$buildingName);
    }
}
