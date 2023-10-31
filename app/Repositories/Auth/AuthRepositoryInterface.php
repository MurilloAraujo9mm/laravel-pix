<?php

namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    /**
     * Register a new user.
     * @param  array  $data  The user's registration data.
     * @return object  The created user object.
     */
    public function register(array $data): object;

    /**
     * Find a user by their login credentials.
     * @param  array  $credentials  The login credentials (typically email and password).
     * @return object|null  The user object if found, or null if not.
     */
    public function findByCredentials(array $credentials): ?object;

    /**
     * Add a JWT token to the blacklist.
     * @param  string  $token  The JWT token to be blacklisted.
     * @return void
     */
    public function addToBlacklist(string $token): void;

    /**
     * Check if a JWT token is already in the blacklist.
     * @param  string  $token  The JWT token to be checked.
     * @return bool  True if the token is blacklisted, false otherwise.
     */
    public function checkIfTokenIsBlacklisted(string $token): bool;
}
