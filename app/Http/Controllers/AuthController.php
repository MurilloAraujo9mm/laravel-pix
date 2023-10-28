<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /** 
     * The authentication service instance.
     */
    protected AuthService $authService;

    /**
     * Create a new AuthController instance.
     * @param  AuthService  $authService
     */
    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Register a new user and provide a token.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $token = $this->authService->register($request->only('name', 'email', 'password'));
        return response()->json(['token' => $token]);
    }

    /**
     * Authenticate a user and provide a token.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $token = $this->authService->login($request->only('email', 'password'));

        if (!$token) {
            return response()->json(['error' => 'Invalid credentials'], Response::HTTP_UNAUTHORIZED);
        }

        return response()->json(['token' => $token], Response::HTTP_OK);
    }

    /**
     * Logout the user and invalidate the token.
     * @param  Request  $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        try {
            $token = JWTAuth::getToken();

            if ($this->authService->isTokenBlacklisted($token)) {
                return response()->json(['message' => 'Token is already in the blacklist.'], Response::HTTP_CONFLICT);
            }

            $this->authService->logout($token);
            return response()->json(['message' => 'Logout successful'], Response::HTTP_OK);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to logout, please try again.', 'error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
}
