<?php

namespace App\Models;


class House extends BaseModel
{
    protected $casts = [
      'owner_info' => 'array',
    ];
}
