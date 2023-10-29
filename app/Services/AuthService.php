<?php

namespace App\Services;

use App\Repositories\Interfaces\AuthRepositoryInterface;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;

class AuthService
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(array $data): string
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->authRepository->register($data);
        return JWTAuth::fromUser($user);
    }

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
    

    public function logout(string $token): void
    {
        if (!$this->authRepository->checkIfTokenIsBlacklisted($token)) {
            $this->authRepository->addToBlacklist($token);
            JWTAuth::invalidate($token);
        }
    }

    public function isTokenBlacklisted(string $token): bool
    {
        return $this->authRepository->checkIfTokenIsBlacklisted($token);
    }
}
