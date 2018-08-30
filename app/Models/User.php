<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    // 如果使用的是非递增或者非数字的主键，则必须在模型上设置
    public $incrementing = false;

    // 主键
    protected $primaryKey = 'guid';

    // 主键类型
    protected $keyType = 'string';

    protected $guarded = [];


    //获取token
    public function getAccessTokenAttribute()
    {
        $token = $this->token();
        return $token->id;
    }

    //自定义授权字段
    public function findForPassport($username)
    {
        return User::where('tel', $username)->first();
    }

    // 用户关联角色
    public function role()
    {
        return $this->hasOne(Role::class, 'guid', 'role_guid');
    }

    //用户关联归属
    public function companyFramework()
    {
        return $this->belongsTo('App\Models\CompanyFramework','rel_guid','guid');
    }

    public function detailInfo()
    {
        return $this->hasOne(UserInfo::class, 'user_guid', 'guid');
    }

}
