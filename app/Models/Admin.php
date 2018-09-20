<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Admin extends Authenticatable
{
    use Notifiable, HasApiTokens;

    // 如果使用的是非递增或者非数字的主键，则必须在模型上设置
    public $incrementing = false;

    // 主键
    protected $primaryKey = 'guid';

    // 主键类型
    protected $keyType = 'string';

    protected $guarded = [];

    // 自定义授权用户名（默认为登录账号）
    public function findForPassport($username)
    {
        return Admin::where('name', $username)->first();
    }

    // 获取token令牌
    public function getAccessTokenAttribute()
    {
        $token = $this->token();
        return $token->id;
    }

}
