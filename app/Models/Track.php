<?php

namespace App\Models;


class Track extends BaseModel
{
    public function user()
    {
        return $this->hasOne(User::class, 'guid', 'user_guid');
    }
}
