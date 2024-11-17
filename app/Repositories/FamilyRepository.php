<?php

namespace App\Repositories;

use Exception;
use App\Models\Family;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\FamilyRepositoryInterface;

class FamilyRepository implements FamilyRepositoryInterface
{
    protected $family;

    public function __construct(Family $family)
    {
        $this->family = $family;
    }

    public function getAllFamilies(): object
    {
        return $this->family->orderBy('id', 'DESC')->get();
    }

    public function getFamilyById(int $id): object
    {
        $family = $this->family->where('id', $id)->first();
        if(!$family) throw new Exception;

        return $family;
    }

    public function createFamily(array $data): object
    {
        DB::beginTransaction();

        try {
            
            $family = $this->family->create($data);

            DB::commit();

            return $family;

        } catch (Exception $e) {

           DB::rollBack();

           throw new Exception;
        }
    }

    public function updateFamily(int $id, array $data): object
    {
        DB::beginTransaction();

        try {
            
            $family = $this->getFamilyById($id);
            $family->update($data);

            DB::commit();

            return $family;

        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception;
        }
    }

    public function deleteFamily(int $id): object
    {
        try {

            $family = $this->getFamilyById($id);
            $family->tasks()->delete();
            $family->users()->detach();
            $family->delete();

            return $family;

        } catch (Exception $e) {

            throw new Exception;
            
        }
    }

    public function addMember(int $familyId, int $userId): void
    {
        try {
            $family = $this->getFamilyById($familyId);
            $family->users()->syncWithoutDetaching($userId);

        } catch (Exception $e) {
            throw new Exception;
        }
    }

    public function removeMember(int $familyId, int $userId): void
    {
        try {
            $family = $this->getFamilyById($familyId);
            $family->users()->detach($userId);

        } catch (Exception $e) {
            throw new Exception;
        }
    }

    public function getFamilyByUserId(int $id): object
    {
        $family = Family::whereRelation('users', 'user_id', $id)->get();
        return $family;
    }
}