<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Hash;

class SafeValidate
{
    // 安全验证
    public function handle
    (
        $request,
        Closure $next
    )
    {
        try {
            if (!Hash::check('chulouwang'.date('Y-m-d',time()),$request->header('safeString'))) {
                return response()->json(["message" => "认证失败"],401);
            }
        } catch (\Exception $e) {
            \Log::info($e->getFile().$e->getLine().$e->getMessage());
            return response()->json(["message" => "认证失败"],401);
        }

        return $next($request);
    }
}