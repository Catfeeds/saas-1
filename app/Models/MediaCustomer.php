<?php

namespace App\Models;

class MediaCustomer extends BaseModel
{
    protected $connection = 'media';

    protected $table = 'customs';

    protected $dates = ['deleted_at'];

    protected $guarded = [];

    // 维护人关联
    public function user()
    {
        return $this->belongsTo(MediaUser::class, 'guardian', 'id');
    }
}
