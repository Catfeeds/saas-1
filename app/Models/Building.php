<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    protected $table = 'buildings';

    protected $guarded = [];

    protected $connection = 'buildings';
}
