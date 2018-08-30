<?php

namespace App\Models;


class Visit extends BaseModel
{
    protected $appends = [ 'time_cn'];

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
