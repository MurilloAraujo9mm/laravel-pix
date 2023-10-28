<?php

namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

/**
 * Class UserRepository
 * Handles the data access logic for the User model.
 * @package App\Repositories
 */
class UserRepository implements UserRepositoryInterface
{
    /**
     * Fetch all users.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Create a new user record.
     * @param array $data
     * @return \App\Models\User
     */
    public function create(array $data): User
    {
        return User::create($data);
    }

    /**
     * Find a user by its primary key.
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Find a user by its email.
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Fetch the most recently created user.
     * @return \App\Models\User|null
     */
    public function findLatest(): ?User
    {
        return User::latest()->first();
    }
}
