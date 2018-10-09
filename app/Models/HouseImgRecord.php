<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HouseImgRecord extends Model
{
    protected $connection = 'media';

    protected $table = 'house_img_records';

    protected $guarded = [];

    protected $casts = [
        'indoor_img' => 'array'
    ];

    // 关联人员
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
