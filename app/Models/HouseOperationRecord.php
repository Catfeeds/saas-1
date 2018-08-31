<?php

namespace App\Models;

class HouseOperationRecord extends BaseModel
{

    protected $appends = ['img_cn'];

    //关联user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_guid', 'guid');
    }

    public function getImgCnAttribute()
    {
        return collect($this->img)->map(function($img) {
            return [
                'value' => $img,
                'url' => config('setting.qiniu_url'). $img. config('setting.qiniu_suffix')
            ];
        });
    }
}
