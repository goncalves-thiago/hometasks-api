<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Family;
use Illuminate\Http\Request;
use App\Services\FamilyService;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;


class FamilyController extends Controller
{
    protected $familyService;

    public function __construct(FamilyService $familyService)
    {
        $this->familyService = $familyService;
    }

    public function index(): JsonResponse
    {
        $families = $this->familyService->getAllFamilies();

        return response()->json([
            'status' => true,
            'families' => $families,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        try {

            $family = $this->familyService->getFamilyById($id);

            return response()->json([
                'status' => true,
                'family' => $family,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Família não encontrada.',
            ], 404);            
        }        
    }

    public function store(Request $request): JsonResponse
    {
        try {
                                 
            $family = $this->familyService->createFamily($request->all());

            return response()->json([
                'status' => true,
                'family' => $family,
                'message' => 'Família cadastrada com sucesso!',
            ], 201);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Família não cadastrada.',
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            
            $family = $this->familyService->updateFamily($id, $request->all());

            return response()->json([
                'status' => true,
                'family' => $family,
                'message' => 'Família editada com sucesso!',
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => "Família não editada."
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            
            $family = $this->familyService->deleteFamily($id);

            return response()->json([
                'status' => true,
                'family' => $family,
                'message' => 'Família apagada com sucesso!'
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Família não apagada!'
            ], 400);
        }
    }

    public function addMember(int $familyId, Request $request): JsonResponse
    {
        try {
            
            $family = $this->familyService->addMember($familyId, $request->userId);

            return response()->json([
                'status' => true,
                'message' => 'Membro adicionado com sucesso!',
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Membro não adicionado.',
            ], 400);            
        }
    }

    public function removeMember(int $familyId, Request $request): JsonResponse
    {
        try {
            
            $family = $this->familyService->removeMember($familyId, $request->userId);

            return response()->json([
                'status' => true,
                'message' => 'Membro removido com sucesso!',
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Membro não removido.',
            ], 400); 
        }
    }

    public function getFamilyTasks(int $familyId): JsonResponse
    {
        try {
            $tasks = $this->familyService->getFamilyTasks($familyId);

            return response()->json([
                'status' => true,
                'tasks' => $tasks,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Nenhuma tarefa localizada.',
            ], 404);
        }
    }

    public function getTasksDashboardByMonth(int $familyId, int $month): JsonResponse
    {
        try {
            $tasks = $this->familyService->getTasksDashboardByMonth($familyId, null, $month);

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

    public function getReportByMonth(int $familyId, int $month): JsonResponse
    {
        try {
            $dashboard = $this->familyService->getReportByMonth($familyId, $month);

            return response()->json([
                'status' => true,
                'dashboard' => $dashboard,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro ao gerar o relatório.',
            ], 404);
        }
    }

    public function getFamilyUsers(int $familyId): JsonResponse
    {
        try {
            $family = $this->familyService->getFamilyById($familyId);
            $users = $family->users()->doesntHave("OwnerOf")->orderBy('name', 'DESC')->get();

            return response()->json([
                'status' => true,
                'users' => $users,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Nenhum membro encontrado.',
            ], 404);
        }
    }
}
