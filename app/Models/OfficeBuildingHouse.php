<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OfficeBuildingHouse extends Model
{
    use SoftDeletes;

    protected $connection = 'media';

    protected $table = 'office_building_houses';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    protected $casts = [
        'owner_info' => 'array',
        'support_facilities' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array',
        'indoor_img' => 'array',
        'check_in_time' => 'date',
        'constru_acreage' => 'float',
        'min_acreage' => 'float',
        'rent_price' => 'float',
        'increasing_situation' => 'float',
        'pay_commission' => 'float',
        'storefront' => 'array',
        'see_house_time_cn'
    ];

    protected $appends = [
        'renovation_cn',
        'house_type',
        'office_building_type_cn',
        'house_busine_state_cn',
        'payment_type_cn',
        'split_cn',
        'orientation_cn',
        'prospecting_cn',
        'see_house_time_cn',
        'house_proxy_type_cn',
        'source_cn',
        'certificate_type_cn',
        'rent_price_unit_cn',
        'pay_commission_cn',
        'shortest_lease_cn',
        'rent_free_cn',
        'house_type_img_cn',
        'indoor_img_cn',
        'building_name',
        'register_company_cn',
        'open_bill_cn',
        'house_number_info',
        'address',
        'check_in_time_cn',
        'constru_acreage_cn',
        'rent_price_cn',
        'increasing_situation_cn',
        'min_acreage_cn',
        'guardian_cn',
        'storefronts_cn',
        'tracks_time',
        'house_img_cn',
        'disc_type_cn',
        'see_power_cn',
        'new_house',
        'start_track_time_cn',
        'created_at_cn',
        'rent_time_cn',
        'int_unit_price'
    ];

    protected $hidden = ['owner_info'];

    public function track()
    {
        return $this->hasMany('App\Models\Track','house_id','id');
    }


    //关联人员
    public function user()
    {
        return $this->belongsTo(MediaUser::class,'guardian', 'id');
    }



    /**
     * 说明: 楼座
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * @author 罗振
     */
    public function buildingBlock()
    {
        return $this->belongsTo(MediaBuildingBlock::class);
    }


    // 看房时间
    public function getSeeHouseTimeCnAttribute()
    {
        $str = '';
        switch ($this->see_house_time) {
            case 1:
                $str = '随时';
                break;
            case 2:
                $str = '非工作时间';
                break;
            case 3:
                $str = '电话预约';
                break;
                default;
                break;
        }
        if ($str && $this->see_house_time_remark) {
            $str .= ',看房时间备注:'.$this->see_house_time_remark;
        }
        return $str;
    }





    /**
     * 说明: 户型拼接
     *
     * @return string
     * @use house_type
     * @author 罗振
     */
    public function getHouseTypeAttribute()
    {
        $houseType = '';
        if (!empty($this->room)) {
            $houseType = $this->room.'室';
        }
        if (!empty($this->hall)) {
            $houseType = $houseType.$this->hall.'厅';
        }

        return $houseType;
    }

    /**
     * 说明: 写字楼类型中文
     *
     * @return string
     * @use office_building_type_cn
     * @author 罗振
     */
    public function getOfficeBuildingTypeCnAttribute()
    {
        if ($this->office_building_type == 1) {
            return '纯写字楼';
        } elseif ($this->office_building_type == 2) {
            return '商住楼';
        } elseif ($this->office_building_type == 3) {
            return '商业综合体楼';
        } elseif ($this->office_building_type == 4) {
            return '酒店写字楼';
        } elseif ($this->office_building_type == 5) {
            return '其他';
        } else {
            return '';
        }
    }

    /**
     * 说明: 公私盘中文
     *
     * @return string
     * @use public_private_cn
     * @author 罗振
     */
    public function getPublicPrivateCnAttribute()
    {
        if ($this->public_private == 1) {
            return '店间公盘';
        } elseif ($this->public_private == 2) {
            return '店内公盘';
        } elseif ($this->public_private == 3) {
            return '私盘';
        } else {
            return '';
        }
    }

    /**
     * 说明: 是否可拆分
     *
     * @return string
     * @use split_cn
     * @author 罗振
     */
    public function getSplitCnAttribute()
    {
        if ($this->split == 1) {
            return '可拆分';
        } elseif ($this->split == 2) {
            return '不可拆分';
        } else {
            return '';
        }
    }

    /**
     * 说明: 最短租期中文
     *
     * @return string
     * @use shortest_lease_cn
     * @author 罗振
     */
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
            return '';
        }
    }

    /**
     * 说明: 免租期中文
     *
     * @return string
     * @use rent_free_cn
     * @author 罗振
     */
    public function getRentFreeCnAttribute()
    {
        if ($this->rent_free == 1) {
            return '1个月';
        } elseif ($this->rent_free == 2) {
            return '2个月';
        } elseif ($this->rent_free == 3) {
            return '3个月';
        } elseif ($this->rent_free == 4) {
            return '4个月';
        } elseif ($this->rent_free == 5) {
            return '5个月';
        } elseif ($this->rent_free == 6) {
            return '6个月';
        } elseif ($this->rent_free == 7) {
            return '7个月';
        } elseif ($this->rent_free == 8) {
            return '8个月';
        } elseif ($this->rent_free == 9) {
            return '9个月';
        } elseif ($this->rent_free == 10) {
            return '10个月';
        } elseif ($this->rent_free == 11) {
            return '面谈';
        } else {
            return '';
        }
    }

    /**
     * 说明: 租金单位转换
     *
     * @return string
     * @use rent_price_unit_cn
     * @author 罗振
     */
    public function getRentPriceUnitCnAttribute()
    {
        if ($this->rent_price_unit == 1) {
            return '%';
        } elseif ($this->rent_price_unit == 2) {
            return '元';
        } else {
            return '';
        }
    }


    /**
     * 说明: 户型图拼接url
     *
     * @return static
     * @use house_type_img_cn
     * @author 罗振
     */
    public function getHouseTypeImgCnAttribute()
    {
        return collect($this->house_type_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.qiniu_suffix'),
            ];
        })->values();
    }

    /**
     * 说明: 室内图拼接url
     *
     * @return static
     * @use indoor_img_cn
     * @author 罗振
     */
    public function getIndoorImgCnAttribute()
    {
        return collect($this->indoor_img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.qiniu_suffix')
            ];
        })->values();
    }

    /**
     * 说明: 注册公司中文
     *
     * @return string
     * @use register_company_cn
     * @author 罗振
     */
    public function getRegisterCompanyCnAttribute()
    {
        if ($this->register_company == 1) {
            return '可注册';
        } elseif ($this->register_company == 2) {
            return '不可注册';
        } else {
            return '';
        }
    }

    /**
     * 说明: 可开发票中文
     *
     * @return string
     * @use open_bill_cn
     * @author 罗振
     */
    public function getOpenBillCnAttribute()
    {
        if ($this->open_bill == 1) {
            return '可开发票';
        } elseif ($this->open_bill == 2) {
            return '不可开发票';
        } else {
            return '';
        }
    }

    /**
     * 说明: 租金
     *
     * @return string
     * @use rent_price_cn
     * @author 罗振
     */
    public function getRentPriceCnAttribute()
    {
        return $this->unit_price.'元/㎡';
    }

    public function getIntUnitPriceAttribute()
    {
        return (int)$this->unit_price;
    }
    /**
     * 说明: 递增情况
     *
     * @return string
     * @use increasing_situation_cn
     * @author 罗振
     */
    public function getIncreasingSituationCnAttribute()
    {
        if (empty($this->increasing_situation)) {
            return '';
        } else {
            return $this->increasing_situation.'%';
        }
    }

    /**
     * 说明: 最小面积
     *
     * @return string
     * @use min_acreage_cn
     * @author 罗振
     */
    public function getMinAcreageCnAttribute()
    {
        if (empty($this->min_acreage)) {
            return '';
        } else {
            return $this->min_acreage.'㎡';
        }
    }

    /**
     * 说明: 新老房源
     *
     * @return string
     * @author 罗振
     */
    public function getNewHouseAttribute()
    {
        if (strtotime($this->created_at->format('Y-m-d H:i:s')) >= strtotime('yesterday')){
            return '新';
        } else {
            return '';
        }
    }

    /**
     * 说明: 跟进时间
     *
     * @return false|string
     * @author 罗振
     */
    public function getStartTrackTimeCnAttribute()
    {
        if (!empty($this->start_track_time)) return date('Y-m-d', $this->start_track_time);
    }

    /**
     * 说明: 时间处理
     *
     * @return string
     * @author 罗振
     */
    public function getCreatedAtCnAttribute()
    {
        // 创建房源时间戳
        $createTime = strtotime($this->created_at->format('Y-m-d H:i:s'));

        if (time() > $createTime && time() <= $createTime + 60) {
            return '刚刚';
        } elseif (time() > $createTime + 60 && time() <= $createTime + 3600) {
            // 创建时间在当前时间一分钟于一小时之间
            return '1~59分钟之前';
        } elseif (time() > $createTime + 3600 && strtotime(date("Y-m-d",strtotime("-1 day")) . ' 23:59:59') <= $createTime) {
            // 创建时间在昨天结束于当前时间一分钟之后
            return '今日'.$this->created_at->format('H:i:s');
        } elseif (strtotime(date("Y-m-d",strtotime("-1 day"))  . ' 23:59:59') > $createTime && strtotime(date("Y-m-d",strtotime("-1 day"))) <= $createTime) {
            return '昨日'.$this->created_at->format('H:i:s');
        } elseif (strtotime(date("Y-m-d",strtotime("-1 day"))) > $createTime  &&  strtotime(date('Y-12-31',strtotime('-1 year'))) < $createTime) {
            // 创建时间在去年结束于昨天开始时间
            return $this->created_at->format('m-d H:i:s');
        } else {
            return $this->created_at->format('Y-m-d H:i:s');
        }
    }

    /**
     * 说明: 跟进时间
     *
     * @return false|string
     * @author 罗振
     */
    public function getRentTimeCnAttribute()
    {
        if (!empty($this->rent_time)) return date('Y-m-d', $this->rent_time);
    }

}
