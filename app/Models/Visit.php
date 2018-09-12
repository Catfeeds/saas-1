<?php

namespace App\Models;


class Visit extends BaseModel
{
    protected $appends = [
        'time_cn',
        'visit_img_cn'
    ];

    // 带看人员
    public function user()
    {
        return $this->hasOne(User::class,'guid', 'visit_user');
    }

    // 陪看人员
    public function accompanyUser()
    {
        return $this->hasOne(User::class,'guid', 'accompany');
    }

    // 带看客户
    public function visitCustomerHouse()
    {
        if ($this->model_type == 'App\Models\House') {
            return $this->belongsTo('App\Models\Customer','rel_guid','guid');
        } else {
            return $this->belongsTo('App\Models\House','rel_guid','guid');
        }
    }


    // 关联房源
    public function house()
    {
        return $this->belongsTo(House::class,'rel_guid', 'guid');
    }

    //带看单
    public function getVisitImgCnAttribute()
    {
        if ($this->visit_img) return config('setting.qiniu_url').$this->visit_img.config('setting.qiniu_suffix');
    }

    //带看时间段
    public function getTimeCnAttribute()
    {
        switch ($this->visit_time) {
            case 1:
                return '上午';
                break;
            case 2:
                return '下午';
                break;
            case 3:
                return '晚上';
                break;
                default;
                break;
        }
    }


}
