<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function all();
    public function create(array $data);
    public function findById(int $id);
    public function findByEmail(string $email);
    public function findLatest();
}
