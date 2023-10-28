<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;

class CheckTokenBlacklist
{
    public function handle($request, Closure $next)
    {
        try {
            JWTAuth::parseToken()->check();

        } catch (TokenBlacklistedException $e) {
            return response()->json(['error' => 'Token has been blacklisted'], 401);
        }

        return $next($request);
    }
}
