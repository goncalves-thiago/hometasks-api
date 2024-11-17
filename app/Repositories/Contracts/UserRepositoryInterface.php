<?php

namespace App\Repositories\Contracts;

interface UserRepositoryInterface
{
    public function getAllUsers(): object;
    public function getUserById(int $id): object;
    public function createUser(array $data): object;
    public function updateUser(int $id, array $data): object;
    public function deleteUser(int $id): object;
}

