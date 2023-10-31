<?php

namespace App\Repositories\Auth;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * Register a new user in the database.
     * @param array $data User data to be registered.
     * @return object Created User object.
     */
    public function register(array $data): object
    {
        return User::create($data);
    }

    /**
     * Find a user by given credentials.
     * @param array $credentials Credentials (typically email and password).
     * @return object|null User object if found, null otherwise.
     */
    public function findByCredentials(array $credentials): ?object
    {
        return User::where('email', $credentials['email'])->first();
    }

    /**
     * Add a JWT token to the blacklist table.
     * @param string $token Token to be blacklisted.
     * @return void
     */
    public function addToBlacklist(string $token): void
    {
        DB::table('token_blacklists')->insert(['token' => $token]);
    }

    /**
     * Check if a given JWT token is blacklisted.
     * @param string $token Token to check.
     * @return bool True if token is blacklisted, false otherwise.
     */
    public function checkIfTokenIsBlacklisted(string $token): bool
    {
        return DB::table('token_blacklists')->where('token', $token)->exists();
    }
}
