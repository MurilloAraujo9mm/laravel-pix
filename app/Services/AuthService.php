<?php

namespace App\Services;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

/**
 * Class AuthService
 * Service for authentication-related functionalities.
 */
class AuthService
{
    /** 
     * @var AuthRepositoryInterface
     */
    protected $authRepository;

    /**
     * AuthService constructor.
     * @param AuthRepositoryInterface $authRepository
     */
    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Registers a new user and returns a JWT token.
     * @param array $data
     * @return string
     */
    public function register(array $data): string
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->authRepository->register($data);
        return JWTAuth::fromUser($user);
    }

    /**
     * Logs in a user based on their credentials.
     * @param array $credentials
     * @return array|null
     */
    public function login(array $credentials): ?array
    {
        $user = $this->authRepository->findByCredentials($credentials);
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return null;
        }
    
        $token = JWTAuth::fromUser($user);
    
        return [
            'token' => $token,
            'user' => $user->makeHidden(['password'])
        ];
    }
    
    /**
     * Logs out a user and invalidates their JWT token.
     * @param string $token
     * @return void
     */
    public function logout(string $token): void
    {
        if (!$this->authRepository->checkIfTokenIsBlacklisted($token)) {
            $this->authRepository->addToBlacklist($token);
            JWTAuth::invalidate($token);
        }
    }

    /**
     * Checks if a token is blacklisted.
     * @param string $token
     * @return bool
     */
    public function isTokenBlacklisted(string $token): bool
    {
        return $this->authRepository->checkIfTokenIsBlacklisted($token);
    }
}
