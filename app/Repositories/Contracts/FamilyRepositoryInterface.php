<?php

namespace App\Repositories\Contracts;

use App\Models\Family;

interface FamilyRepositoryInterface
{
    public function getAllFamilies(): object;
    public function getFamilyById(int $id): object;
    public function createFamily(array $data): object;
    public function updateFamily(int $id, array $data): object;
    public function deleteFamily(int $id): object;
    public function addMember(int $familyId, int $userId): void;
    public function removeMember(int $familyId, int $userId): void;
    public function getFamilyByUserId(int $id): object;
}
