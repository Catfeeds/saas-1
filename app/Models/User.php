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

    protected $appends = [
        'pic_cn'
    ];

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

    // 公司
    public function company()
    {
        return $this->belongsTo('App\Models\Company','company_guid','guid');
    }

    // 用户头像
    public function getPicCnAttribute()
    {
        return empty($this->pic) ? config('setting.user_default_img') : config('setting.qiniu_url') . $this->pic;
    }

    // 房源
    public function house()
    {
        return $this->hasMany('App\Models\House','entry_person','guid');
    }

    // 客源
    public function customer()
    {
        return $this->hasMany('App\Models\Customer','entry_person','guid');
    }

    // 看房方式(提交钥匙)
    public function seeHouseWay()
    {
        return $this->hasMany('App\Models\SeeHouseWay','user_guid','guid');
    }


}
