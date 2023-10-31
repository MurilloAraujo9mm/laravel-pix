<?php

namespace App\Http\Controllers;

use App\Services\Auth\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /** The authentication service instance */
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
     * @OA\Post(
     *      path="/api/auth/register",
     *      summary="Register a new user",
     *      description="Register a new user and provide a token",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", description="Name of the user"),
     *              @OA\Property(property="email", type="string", description="Email of the user"),
     *              @OA\Property(property="password", type="string", description="Password for the user")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully registered",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="JWT Token for the user")
     *          )
     *      )
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $token = $this->authService->register($request->only('name', 'email', 'password'));
        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/login",
     *      summary="Authenticate a user",
     *      description="Authenticate a user and provide a token",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", description="Email of the user"),
     *              @OA\Property(property="password", type="string", description="Password for the user")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successfully authenticated",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="JWT Token for the user")
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *          @OA\JsonContent(
     *              @OA\Property(property="error", type="string", description="Error message for unauthorized request")
     *          )
     *      )
     * )
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
     * @OA\Post(
     *      path="/api/v1/logout",
     *      summary="Logout a user",
     *      description="Logout the user and invalidate the token",
     *      tags={"Authentication"},
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Logout success message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Error message for failed logout request"),
     *              @OA\Property(property="error", type="string", description="Detailed error message")
     *          )
     *      ),
     *      @OA\Response(
     *          response=409,
     *          description="Conflict",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", description="Conflict error message")
     *          )
     *      )
     * )
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
