<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClwHouse extends Model
{
    use SoftDeletes;

    protected $connection = 'CLW';

    protected $table = 'houses';

    protected $dates = ['deleted_at'];

    // 如果使用的是非递增或者非数字的主键，则必须在模型上设置
    public $incrementing = false;

    // 主键
    protected $primaryKey = 'guid';

    // 主键类型
    protected $keyType = 'string';

    protected $guarded = [];

    protected $casts = [
        'indoor_img' => 'array',
        'support_facilities' => 'array',
        'owner_info' => 'array',
        'cost_detail' => 'array',
        'house_type_img' => 'array'
    ];
}
