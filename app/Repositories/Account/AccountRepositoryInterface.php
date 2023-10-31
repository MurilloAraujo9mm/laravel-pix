<?php

namespace App\Repositories\Account;

/**
 * Interface AccountRepositoryInterface
 * Defines the contract for Account data access operations.
 * @package App\Repositories\Interfaces
 */
interface AccountRepositoryInterface
{
    /**
     * Retrieve all accounts.
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function all();

    /**
     * Create a new account record.
     * @param array $data - Data for creating a new account.
     * @return \App\Models\Account - The created account.
     */
    public function create(array $data);

    /**
     * Find an account by its primary key.
     * @param int $id - ID of the account to be found.
     * @return \App\Models\Account|null - Found account or null if not found.
     */
    public function findById(int $id);

    /**
     * Retrieve accounts based on user ID.
     * @param int $userId - User ID related to the accounts.
     * @return \Illuminate\Database\Eloquent\Collection|static[] - Found accounts.
     */
    public function findByUserId(int $userId);

    /**
     * Fetch the most recently created account.
     * @return \App\Models\Account|null - Most recent account or null if none found.
     */
    public function findLatest();

    public function getLoggedInUserAccount();

}
