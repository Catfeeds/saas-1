<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        //注册发出访问令牌并撤销访问令牌、客户端和个人访问令牌所必需的路由
        Passport::routes();
        // 定义令牌作用域
        Passport::tokensCan(config('passport.scopes'));
        // 访问令牌有效期（天）
        Passport::tokensExpireIn(Carbon::now()->addDays(config('passport.tokensExpireIn')));
        // 刷新后的访问令牌有效期（天）
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(config('passport.refreshTokensExpireIn')));
    }
}
