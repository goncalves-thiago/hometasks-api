<?php

namespace App\Repositories\Contracts;

use Illuminate\Http\Request;

interface TaskRepositoryInterface
{
    public function getAllTasks(): object;
    public function getTaskById(int $id): object;
    public function createTask(array $data): object;
    public function updateTask(int $id, array $data): object;
    public function deleteTask(int $id): object;
    public function getFamilyTasks(int $familyId, int $userId): object;
    public function getUserDashboardByMonth(int $familyId, int $userId, string $month): object;
    public function getOwnerDashboardByMonth(int $familyId, string $month): object;
    public function getTasksHistory(int $familyId): object;
    public function setAttachment(int $id, Request $request) : object;
    public function removeAttachment(int $id) : object;
}
