<?php

/**
 * CheckTokenBlacklist Middleware
 * This middleware is responsible for checking if the JWT token provided in the request
 * is blacklisted. If the token is blacklisted, a response with a 401 status is returned.
 * Typical Use: Protect routes from being accessed with tokens that have been explicitly
 * invalidated, such as after a logout.
 * @package App\Http\Middleware
 */

 namespace App\Http\Middleware;

 use Closure;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
 use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
 use Symfony\Component\HttpFoundation\Response;

 class CheckTokenBlacklist
 {
     /**
      * Handle an incoming request.
      * @param  \Illuminate\Http\Request  $request  The incoming request.
      * @param  \Closure  $next  The next middleware or action to be executed.
      * @return mixed A JSON response in case of blacklisted token, or proceeds to the next middleware or action.
      */
     public function handle($request, Closure $next): JsonResponse|Response
     {
         try {
             JWTAuth::parseToken()->check();
 
         } catch (TokenBlacklistedException $e) {
             return response()->json(['error' => 'Token has been blacklisted'], Response::HTTP_UNAUTHORIZED);
         }
 
         return $next($request);
     }
 }
 
 