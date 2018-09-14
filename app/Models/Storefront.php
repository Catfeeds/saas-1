<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Storefront extends Model
{
    protected $connection = 'media';

    protected $table = 'storefronts';

    protected $dates = ['deleted_at'];

    protected $guarded = [];
    

}
