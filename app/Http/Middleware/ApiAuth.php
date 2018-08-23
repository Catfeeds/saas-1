<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApiAuth
{
    // 验证
    public function handle($request, Closure $next, $guard = null)
    {
        if (empty(Auth::guard($guard)->user())) {
            return response()->json(["message" => "Unauthenticated."], 401);
        }
        return $next($request);
    }
}
