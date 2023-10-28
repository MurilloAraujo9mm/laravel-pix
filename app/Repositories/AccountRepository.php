<?php

namespace App\Repositories;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;

/**
 * Class AccountRepository
 * Handles the data access logic for the Account model.
 * @package App\Repositories
 */
class AccountRepository implements AccountRepositoryInterface
{
    public function all()
    {
        return Account::all();
    }

    public function create(array $data): Account
    {
        return Account::create($data);
    }

    public function findById(int $id): ?Account
    {
        return Account::find($id);
    }

    public function findByUserId(int $userId)
    {
        return Account::where('user_id', $userId)->get();
    }

    public function findLatest(): ?Account
    {
        return Account::latest()->first();
    }
}
