<?php

namespace App\Repositories\Contracts;

interface TaskRepositoryInterface
{
    public function getAllTasks(): object;
    public function getTaskById(int $id): object;
    public function createTask(array $data): object;
    public function updateTask(int $id, array $data): object;
    public function deleteTask(int $id): object;
    public function getFamilyTasks(int $familyId, int $userId): object;
}
