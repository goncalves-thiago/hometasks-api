<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Request;
use App\Repositories\TaskRepository;

class TaskService
{
    protected $taskRepository;

    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function getAllTasks(): object
    {
        return $this->taskRepository->getAllTasks();
    }

    public function getTaskById(int $id): object
    {
        $task = $this->taskRepository->getTaskById($id);
        if(!$task) throw new Exception;

        return $task;
    }

    public function createTask(array $data): object
    {
        $task = $this->taskRepository->createTask($data);
        if(!$task) throw new Exception;

        return $task;
    }

    public function updateTask(int $id, array $data): object
    {
        $task = $this->taskRepository->getTaskById($id);
        if(!$task) throw new Exception;

        return $this->taskRepository->updateTask($id, $data);
    }

    public function deleteTask(int $id): object
    {
        $task = $this->taskRepository->deleteTask($id);
        if(!$task) throw new Exception;

        return $task;
    }

    
    public function getFamilyTasks(int $family_id, ?int $user_id): object
    {
        $tasks = $this->taskRepository->getFamilyTasks($family_id, $user_id);
        if((!$tasks) || ($tasks->isEmpty())) throw new Exception;

        return $tasks;
    }

    public function getTasksDashboardByMonth(int $family_id, ?int $user_id, string $month): object
    {
        if ($user_id) {

            $tasks = $this->taskRepository->getUserDashboardByMonth($family_id, $user_id, $month);

        } else {

            $tasks = $this->taskRepository->getOwnerDashboardByMonth($family_id, $month);

        }
        
        if((!$tasks) || ($tasks->isEmpty())) throw new Exception;

        return $tasks;
    }

    public function getTasksHistory(int $family_id): object
    {
        $tasks = $this->taskRepository->getTasksHistory($family_id);
        if((!$tasks) || ($tasks->isEmpty())) throw new Exception;

        return $tasks;
    }

    public function setAttachment(int $id, Request $request): object
    {
        $task = $this->taskRepository->setAttachment($id, $request);
        if(!$task) throw new Exception;

        return $task;
    }

    public function removeAttachment(int $id): object
    {
        $task = $this->taskRepository->removeAttachment($id);
        if(!$task) throw new Exception;

        return $task;
    }
}
