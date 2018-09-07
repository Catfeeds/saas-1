<?php

namespace App\Models;

class HouseOperationRecord extends BaseModel
{
    protected $casts = [
        'img' => 'array'
    ];

    protected $appends = [
        'img_cn'
    ];

    // 用户
    public function user()
    {
        return $this->belongsTo('App\Models\User','user_guid','guid');
    }

    // 图片 img_cn
    public function getImgCnAttribute()
    {
        return collect($this->img)->map(function ($img) {
            return [
                'name' => $img,
                'url' => config('setting.qiniu_url') . $img . config('setting.qiniu_suffix'),
            ];
        })->values();
    }
}
