<?php

namespace App\Repositories;

use App\Handler\Common;
use Illuminate\Database\Eloquent\Model;

class CompanyFrameworksRepository extends Model
{

    protected $user;

    public function __construct()
    {
        $this->user = Common::user();
    }

    public function getList($request)
    {
        
    }
}