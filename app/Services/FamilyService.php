<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
use App\Services\TaskService;
use Illuminate\Support\Facades\Auth;
use App\Repositories\FamilyRepository;
use Illuminate\Database\Eloquent\Collection;

class FamilyService
{
    protected $familyRepository;
    protected $taskService;

    public function __construct(FamilyRepository $familyRepository, TaskService $taskService)
    {
        $this->familyRepository = $familyRepository;
        $this->taskService = $taskService;
    }

    public function getAllFamilies(): object
    {
        return $this->familyRepository->getAllFamilies();
    }

    public function getFamilyById(int $id): object
    {
        $family = $this->familyRepository->getFamilyById($id);
        if(!$family) throw new Exception;

        return $family;
    }

    public function createFamily(array $data): object
    {
        // Add the Family owner id to the array
        $dataWithOwnerId = Arr::add($data, 'owner_id', Auth::user()->id);

        // Create the family
        $family = $this->familyRepository->createFamily($dataWithOwnerId);
        
        if(!$family) throw new Exception;

        // Add user as family member
        $this->addMember($family->id, Auth::user()->id);

        return $family;
    }

    public function updateFamily(int $id, array $data): object
    {
        $family = $this->familyRepository->getFamilyById($id);
        if(!$family) throw new Exception;

        return $this->familyRepository->updateFamily($id, $data);
    }

    public function deleteFamily(int $id): object
    {
        $family = $this->familyRepository->deleteFamily($id);
        if(!$family) throw new Exception;

        return $family;
    }

    public function addMember(int $familyId, int $userId): void
    {
        $family = $this->familyRepository->getFamilyById($familyId);
        if ((!$family) || ($family->users->find($userId))) throw new Exception;
    
        $this->familyRepository->addMember($familyId, $userId);
    }

    public function removeMember(int $familyId, int $userId): void
    {
        $family = $this->familyRepository->getFamilyById($familyId);
        if ((!$family) || ($family->owner_id == $userId) || (!$family->users->find($userId))) throw new Exception;

        $family->users->find($userId)->tasks()->delete();

        $this->familyRepository->removeMember($familyId, $userId);
    }

    public function getFamilyTasks(int $familyId): object
    {
        $userId = Auth::user()->id;
        $family = $this->getFamilyById($familyId);

        if($userId == $family->owner_id) {

            $tasks = $this->taskService->getFamilyTasks($familyId, $userId == null);

        } else {

            $tasks = $this->taskService->getFamilyTasks($familyId, $userId);
        }
        
        if((!$tasks) || ($tasks->isEmpty())) throw new Exception;
        
        return $tasks;
    }

    public function getTasksDashboardByMonth(int $familyId, ?int $userId, string $month): object
    {
        if(!$userId)
            $userId = Auth::user()->id;

        $family = $this->getFamilyById($familyId);

        if ($userId == $family->owner_id) {

            $tasks = $this->taskService->getTasksDashboardByMonth($familyId, $userId == null, $month);

        } else {

            $tasks = $this->taskService->getTasksDashboardByMonth($familyId, $userId, $month);
        }
        
        if((!$tasks) || ($tasks->isEmpty())) throw new Exception;
        
        return $tasks;
    }

    public function getReportByMonth(int $familyId, string $month): object
    {
        $family = $this->getFamilyById($familyId);
        $users = $family->users()->doesntHave("OwnerOf")->orderBy('name', 'ASC')->get();
 
        $dashboard = new Collection();

        foreach($users as $user) {
            $userDashboard = $this->getTasksDashboardByMonth($familyId, $user->id, $month);

            $data = [
                'user' => $user,
                'tasks' => $userDashboard,
            ];
            
            $dashboard->push($data);
        }
        
        if((!$dashboard) || ($dashboard->isEmpty())) throw new Exception;
        
        return $dashboard;
    }

}
