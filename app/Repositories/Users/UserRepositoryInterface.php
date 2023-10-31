<?php

namespace App\Repositories\Users;

/**
 * Interface UserRepositoryInterface
 * Repository interface for user-related operations.
 */
interface UserRepositoryInterface
{
    /**
     * Fetch all users.
     * @return \Illuminate\Database\Eloquent\Collection|\App\Models\User[] List of users.
     */
    public function all();

    /**
     * Create a new user.
     * @param array $data User data.
     * @return \App\Models\User Created user instance.
     */
    public function create(array $data);

    /**
     * Find a user by its unique identifier.
     * @param int $id User ID.
     * @return \App\Models\User|null User instance or null if not found.
     */
    public function findById(int $id);

    /**
     * Find a user by its email address.
     * @param string $email User email address.
     * @return \App\Models\User|null User instance or null if not found.
     */
    public function findByEmail(string $email);

    /**
     * Fetch the most recently created user.
     * @return \App\Models\User|null Latest user instance or null if no users found.
     */
    public function findLatest();
}
