<?php

namespace App\Services;

use App\Models\Account;
use App\Repositories\Interfaces\AccountRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

/**
 * Class AccountService
 * Provides business logic related to accounts.
 * @package App\Services
 */
class AccountService
{

    const ACCOUNT_NUMBER_LENGTH = 12;
    const ALLOWED_CHARACTERS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /** @var AccountRepositoryInterface */
    protected $accountRepository;

    /**
     * AccountService constructor.
     * @param AccountRepositoryInterface $accountRepository
     */
    public function __construct(AccountRepositoryInterface $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * Fetch all accounts.
     * @return Collection|Account[] - A collection of Account objects.
     */
    public function getAllAccounts(): Collection
    {
        return $this->accountRepository->all();
    }

    /**
     * Create a new account.
     * @param array $data - The data to use when creating the account.
     * @return Account - The newly created account.
     */
    public function createAccount(array $data): Account
    {
        
        return $this->accountRepository->create($data);
    }

    /**
     * Retrieve an account by its ID.
     * @param int $id - The ID of the account to retrieve.
     * @return Account|null - The account found or null if none found.
     */
    public function getAccountById(int $id): ?Account
    {
        return $this->accountRepository->findById($id);
    }

    /**
     * Retrieve all accounts associated with a specific user ID.
     * @param int $userId - The ID of the user for which to retrieve accounts.
     * @return Collection|Account[] - A collection of Account objects for the user.
     */
    public function getAccountsByUserId(int $userId): Collection
    {
        return $this->accountRepository->findByUserId($userId);
    }

    /**
     * Fetch the most recently created account.
     * @return Account|null - The most recent account or null if none found.
     */
    public function getLatestAccount(): ?Account
    {
        return $this->accountRepository->findLatest();
    }

    /**
     * Generate a random account number.
     * @return string
     */
    public function generateRandomAccountNumber(): string
    {
        $accountNumber = '';
        for ($i = 0; $i < self::ACCOUNT_NUMBER_LENGTH; $i++) {
            $accountNumber .= $this->getRandomCharacter();
        }
        return $accountNumber;
    }

    /**
     * Get a random character from the allowed characters string.
     * @return string
     */
    private function getRandomCharacter(): string
    {
        $index = rand(0, strlen(self::ALLOWED_CHARACTERS) - 1);
        return $index;
    }
}
