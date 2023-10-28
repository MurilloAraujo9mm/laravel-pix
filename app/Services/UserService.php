<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Repositories\AccountRepository;
use Illuminate\Support\Str;

class UserService
{
    protected $userRepository;
    protected $accountRepository;

    public function __construct(UserRepository $userRepository, AccountRepository $accountRepository)
    {
        $this->userRepository = $userRepository;
        $this->accountRepository = $accountRepository;
    }

    public function getAllUsers()
    {
        return $this->userRepository->all();
    }

    public function createUser(array $data)
    {
        $user = $this->userRepository->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        if (!$user) {
            throw new \Exception('Falha ao criar o usuário.');
        }

        $this->accountRepository->create(
            $user->id, 
            $data['balance'], 
            $this->generateRandomAccountNumber()
        );

        return $user;
    }

    protected function generateRandomAccountNumber()
    {
        return Str::random(12);
    }

    /**
     * Find a user by its ID.
     * @param int $id
     * @return \App\Models\User|null
     */
    public function findUserById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Find a user by its email address.
     * @param string $email
     * @return \App\Models\User|null
     */
    public function findUserByEmail(string $email)
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Get the most recently created user.
     * @return \App\Models\User|null
     */
    public function findLatestUser()
    {
        return $this->userRepository->findLatest();
    }
}
