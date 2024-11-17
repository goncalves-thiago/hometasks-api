<?php

namespace App\Http\Controllers\Api;

use Exception;
use Illuminate\Http\Request;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    protected $taskService;

    public function __construct(TaskService $taskService)
    {
        $this->taskService = $taskService;
    }

    function index(): JsonResponse
    {
        $tasks = $this->taskService->getAllTasks();

        return response()->json([
            'status' => true,
            'tasks' => $tasks,
        ], 200);
    }

    function show(int $id): JsonResponse
    {
        try {

            $task = $this->taskService->getTaskById($id);

            return response()->json([
                'status' => true,
                'task' => $task,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => true,
                'message' => 'Task não encontrada.',
            ], 404);            
        }        
    }

    function store(Request $request): JsonResponse
    {
        try {
                                 
            $task = $this->taskService->createTask($request->all());

            return response()->json([
                'status' => true,
                'task' => $task,
                'message' => 'Task cadastrada com sucesso!',
            ], 201);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Task não cadastrada.',
            ], 404);
        }
    }

    public function update(Request $request, int $id) : JsonResponse 
    {
        try {
            
            $task = $this->taskService->updatetask($id, $request->all());

            return response()->json([
                'status' => true,
                'task' => $task,
                'message' => 'Task editada com sucesso!',
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => "Task não editada."
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            
            $task = $this->taskService->deleteTask($id);

            return response()->json([
                'status' => true,
                'task' => $task,
                'message' => 'Task apagada com sucesso!'
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Task não apagada!'
            ], 400);
        }
    }

    public function getTasksHistory(int $familyId): JsonResponse
    {
        try {
            $tasks = $this->taskService->getTasksHistory($familyId);
            
            return response()->json([
                'status' => true,
                'tasks' => $tasks,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Nenhuma task localizada.',
            ], 404);
        }
    }

    public function addAttachment(Request $request, int $id): JsonResponse
    {
        try {
            
            $task = $this->taskService->setAttachment($id, $request);
            
            return response()->json([
                'status' => true,
                'task' => $task,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro durante o upload do attachment.',
            ], 404);
        }
    }

    public function removeAttachment(int $id): JsonResponse
    {
        try {
            
            $task = $this->taskService->removeAttachment($id);
            
            return response()->json([
                'status' => true,
                'task' => $task,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro ao remover o attachment.',
            ], 404);
        }
    }
}
