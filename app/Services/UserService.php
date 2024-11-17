<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Family;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;

class UserService
{
    protected $userRepository;
    protected $taskService;
    protected $familyService;

    public function __construct(UserRepository $userRepository, TaskService $taskService, FamilyService $familyService)
    {
        $this->userRepository = $userRepository;
        $this->taskService = $taskService;
        $this->familyService = $familyService;
    }

    public function getAllUsers()
    {
        return $this->userRepository->getAllUsers();
    }

    public function getUserById(int $id)
    {
        $user = $this->userRepository->getUserById($id);
        
        if(!$user) throw new Exception;
        
        return $user;
    }

    public function createUser(array $data)
    {        
        $user = $this->userRepository->createUser($data);
        if(!$user) throw new Exception;

        return $user;
    }

    public function updateUser(int $id, Request $request)
    {
        $user = $this->userRepository->getUserById($id);
        if (!$user) throw new Exception;

        if($request->password) {
            $request->session()->forget('password_hash_web');
            $user = $this->userRepository->updateUser($id, $request->all());
            Auth::guard('web')->login($user);
        } else {
            $user = $this->userRepository->updateUser($id, $request->all());
        }

        return $user;       
    }

    public function deleteUser(int $id)
    {
        $user = $this->userRepository->deleteUser($id);
        if(!$user) throw new Exception;
        
        return $user;
    }

    public function getAllowance(int $userId, int $month)
    {
        $family = Family::has('owner', $userId)->first();
        if($family) return $this->getAllowanceToPay($userId, $family, $month);
        else return $this->getAllowanceToReceive($userId, $month);
    }
    
    public function getAllowanceToReceive(int $userId, int $month)
    {
        $family = Family::whereRelation('users', 'user_id', $userId)->first();
        if(!$family) return 0;

        $tasks = $this->taskService->getTasksDashboardByMonth($family->id, $userId, $month);
        $tasksTotal = $tasks["completed"] + $tasks["expired"];
        
        if ($tasks["expired"] === 0) return $family->allowance;
        else {
            $taskPrice = $family->allowance / $tasksTotal;
            return $family->allowance - ($taskPrice * $tasks["expired"]);
        }
    }

    public function getAllowanceToPay(int $userId, Family $family, int $month)
    {
        $familyMembers = $family->users()->where('user_id', '!=', $userId)->get();

        $allowance = 0;
        
        foreach($familyMembers as $member) {
            $allowance += $this->getAllowanceToReceive($member->id, $month);
        }

        return $allowance;
    }

    public function getUsersWithoutFamily($query)
    {
        $users = User::doesntHave("families")->where('email', 'LIKE', '%'.$query.'%')->get();
        if (!$users) throw new Exception;

        return $users;
    }

    public function addAvatar(int $id, Request $request): object
    {
        $user = $this->userRepository->addAvatar($id, $request);
        if(!$user) throw new Exception;

        return $user;
    }

    public function removeAvatar(int $id, Request $request): object
    {
        $user = $this->userRepository->removeAvatar($id, $request);
        if(!$user) throw new Exception;

        return $user;
    }
}