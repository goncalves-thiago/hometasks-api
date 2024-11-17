<?php

namespace App\Repositories;

use Exception;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Contracts\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    protected $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function getAllTasks(): object
    {
        return $this->task->orderBy('id', 'DESC')->get();
    }

    public function getTaskById(int $id): object
    {
        $task = $this->task->where('id', $id)->first();
        if(!$task) throw new Exception;

        return $task;
    }

    public function createTask(array $data): object
    {
        DB::beginTransaction();

        try {
            
            $task = $this->task->create($data);

            DB::commit();

            return $task;

        } catch (Exception $e) {

           DB::rollBack();

           throw new Exception;
        }
    }

    public function updateTask(int $id, array $data): object
    {
        DB::beginTransaction();

        try {
            
            $task = $this->getTaskById($id);
            $task->update($data);

            DB::commit();

            return $task;

        } catch (Exception $e) {

            DB::rollBack();

            throw new Exception;
        }
    }

    public function deleteTask(int $id): object
    {
        try {

            $task = $this->getTaskById($id);

            // Delete file from public folder
            if($task->attachmentUrl) {
                $file_path = public_path().'/'.$task->attachmentUrl;
                unlink($file_path);
            }

            $task->delete();

            return $task;

        } catch (Exception $e) {

            throw new Exception;
            
        }
    }

    public function getFamilyTasks(int $familyId, ?int $userId): object
    {
        
        try {
          
          $tasks = null;
        
          if($userId) {
            $tasks = Task::where('family_id', $familyId)
            ->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->get();

          } else {

            $tasks = Task::where('family_id', $familyId)
            ->orderBy('created_at', 'DESC')
            ->get();
          }

            return $tasks;
    
        } catch (Exception $e) {

            throw new Exception;
        }
    }

    public function getUserDashboardByMonth(int $familyId, int $userId, string $month): object
    {
        try {

            $pending = Task::where('family_id', $familyId)
                ->where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->where('status', 'pending')
                ->whereDate('deadline', '>=', now())
                ->get()
                ->count();

            $inProgress = Task::where('family_id', $familyId)
                ->where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->where('status', 'inProgress')
                ->whereDate('deadline', '>=', now())
                ->get()
                ->count();

            $inReview = Task::where('family_id', $familyId)
                ->where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->where('status', 'inReview')
                ->get()
                ->count();
            
            $completed = Task::where('family_id', $familyId)
                ->where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->where('status', 'completed')
                ->get()
                ->count();

            $expired = Task::where('family_id', $familyId)
                ->where('user_id', $userId)
                ->whereMonth('created_at', $month)
                ->whereDate('deadline', '<', now())
                ->where(function ($query) {
                    $query->whereLike('status', 'pending')
                          ->orWhereLike('status', 'inProgress');
                })
                ->get()
                ->count();

            return collect([
                'pending' => $pending,
                'inProgress' => $inProgress,
                'inReview' => $inReview,
                'completed' => $completed,
                'expired' => $expired,
            ]);
                
    
        } catch (Exception $e) {

            throw new Exception;
        }
    }

    public function getOwnerDashboardByMonth(int $familyId, string $month): object
    {
        try {

            $pending = Task::where('family_id', $familyId)
            ->whereMonth('created_at', $month)
            ->where('status', 'pending')
            ->whereDate('deadline', '>=', now())
            ->get()
            ->count();

        $inProgress = Task::where('family_id', $familyId)
            ->whereMonth('created_at', $month)
            ->where('status', 'inProgress')
            ->whereDate('deadline', '>=', now())
            ->get()
            ->count();

        $inReview = Task::where('family_id', $familyId)
            ->whereMonth('created_at', $month)
            ->where('status', 'inReview')
            ->get()
            ->count();
        
        $completed = Task::where('family_id', $familyId)
            ->whereMonth('created_at', $month)
            ->where('status', 'completed')
            ->get()
            ->count();

        $expired = Task::where('family_id', $familyId)
            ->whereMonth('created_at', $month)
            ->whereDate('deadline', '<', now())
            ->where(function ($query) {
                $query->whereLike('status', 'pending')
                      ->orWhereLike('status', 'inProgress');
            })
            ->get()
            ->count();

            return collect([
                'pending' => $pending,
                'inProgress' => $inProgress,
                'inReview' => $inReview,
                'completed' => $completed,
                'expired' => $expired,
            ]);

        } catch (Exception $e) {
            throw new Exception;
        }
    }

    public function getTasksHistory(int $familyId): object
    {
        try {

            $completed = Task::where('family_id', $familyId)->where('status', '=', 'completed')->get()->count();

            $expired = Task::where('family_id', $familyId)
                ->whereDate('deadline', '<', now())
                ->where(function ($query) {
                    $query->whereLike('status', 'pending')
                          ->orWhereLike('status', 'inProgress');
                })
                ->get()
                ->count();

            $total = $completed + $expired;

            return collect([
                'total' => $total,
                'totalCompleted' => $completed,
                'totalExpired' => $expired,
            ]);            
    
        } catch (Exception $e) {

            throw new Exception;
        }
    }

    public function setAttachment(int $id, Request $request) : object
    {
        // Save file to public folder
        $attachmentName = time().'.'.$request->file('file')->extension();
        $request->file('file')->move(public_path('attachments'), $attachmentName);

        // Update task object field attachmentURL
        $task = $this->getTaskById($id);
        $task->attachmentUrl = 'attachments/'.$attachmentName;
        $task->save();

        return $task;
    }

    public function removeAttachment(int $id) : object
    {
        // Get task
        $task = $this->getTaskById($id);
        
        // Delete file from public folder
        $file_path = public_path().'/'.$task->attachmentUrl;
        unlink($file_path);

        // Update task object field attachmentURL
        $task->attachmentUrl = null;
        $task->save();

        return $task;
    }
}