<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaUser extends Model
{
    protected $connection = 'media';

    protected $table = 'users';

    protected $dates = ['deleted_at'];

    protected $guarded = [];
    
    // 关联门店
    public function storefront()
    {
        return $this->belongsTo(Storefront::class,'ascription_store', 'id');
    }
    
    
}
