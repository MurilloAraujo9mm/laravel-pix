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
    /**
     * Retrieve all accounts.
     * @return \Illuminate\Database\Eloquent\Collection|Account[]
     */
    public function all()
    {
        return Account::all();
    }

    /**
     * Create a new account record in the database.
     * @param array $data Account data to store.
     *
     * @return Account The newly created Account instance.
     */
    public function create(array $data): Account
    {
        return Account::create($data);
    }

    /**
     * Find an account by its ID.
     * @param int $id The ID of the account.
     * @return Account|null Returns an Account instance if found, or null if not.
     */
    public function findById(int $id): ?Account
    {
        return Account::find($id);
    }

    /**
     * Find accounts associated with a given user ID.
     * @param int $userId The ID of the user.
     * @return \Illuminate\Database\Eloquent\Collection|Account[] Returns a collection of Account instances.
     */
    public function findByUserId(int $userId)
    {
        return Account::where('user_id', $userId)->get();
    }

    /**
     * Retrieve the latest created account.
     * @return Account|null Returns the latest created Account instance if found, or null if not.
     */
    public function findLatest(): ?Account
    {
        return Account::latest()->first();
    }

    /**
     * @return Account|null Returns user logged auth
     */
    public function getLoggedInUserAccount(): ?Account
    {
        $userId = auth()->user()->id;
        return Account::where('user_id', $userId)->first();
    }
}
