<?php

namespace App\Models;


class Customer extends BaseModel
{
    protected $casts = [
        'customer_info' => 'array',
        'intention' => 'array',
        'block' => 'array',
        'building' => 'array',
        'house_type' => 'array',
    ];
}
