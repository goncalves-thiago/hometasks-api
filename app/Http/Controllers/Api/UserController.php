<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Family;
use Illuminate\Http\Request;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;
    
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;   
    }

    public function index(): JsonResponse
    {
        $users = $this->userService->getAllUsers();
        
        return response()->json([
            'status' => true,
            'users' => $users,
        ], 200);
    }

    public function show(int $id): JsonResponse
    {
        try {

            $user = $this->userService->getUserById($id);

            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);

        } catch (Exception $e) {
            

        }

    }

    public function store(UserRequest $request): JsonResponse
    {
        try {

            $user = $this->userService->createUser($request->all());

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário cadastrado com sucesso!"
            ], 201);
        
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => "Usuário não cadastrado."
            ], 404);
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            
            $user = $this->userService->updateUser($id, $request);

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário editado com sucesso!"
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => "Usuário não editado!",
            ], 400);
        }
    }

    public function destroy(int $id): JsonResponse
    {

        try {
            
            $user = $this->userService->deleteUser($id);

            return response()->json([
                'status' => true,
                'user' => $user,
                'message' => "Usuário apagado com sucesso!",
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => "Usuário não apagado!",
            ], 400);

        }
    }

    public function getUserData(): JsonResponse
    {
        // Get user data
        $user = Auth::user();

        // Get user family
        $family = Family::whereRelation('users', 'user_id', $user->id)->first();
        $familyId = ($family) ? $family->id : null;       
        $isOwner = (($family) && ($family->owner_id == $user->id)) ? true : false;

        return response()->json([
            'status' => true,
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'familyId' => $familyId,
            'isOwner' => $isOwner,
            'avatarUrl' => $user->avatarUrl,
            'token' => $user->tokens->first()->token,
        ], 200);
    }

    public function getAllowanceToReceive(int $userId, int $month): JsonResponse
    {
        try {
             $allowance = $this->userService->getAllowance($userId, $month);

            return response()->json([
                'status' => true,
                'allowance' => $allowance,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Não foi possível calcular o valor da mesada.',
            ], 404);
        }
    }

    public function getUsersWithoutFamily(string $query): JsonResponse
    {
        try {

            $users = $this->userService->getUsersWithoutFamily($query);

            return response()->json([
                'status' => true,
                'users' => $users,
            ], 200);

        } catch (Exception $e) {
            
            return response()->json([
                'status' => false,
                'message' => 'Não foram encontrados usuários sem família.',
            ], 404);
        }
    }

    public function addAvatar(Request $request, int $id): JsonResponse
    {
        try {
            
            $user = $this->userService->addAvatar($id, $request);
            
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro durante o upload do avatar.',
            ], 404);
        }
    }

    public function removeAvatar(Request $request, int $id): JsonResponse
    {
        try {
            
            $user = $this->userService->removeAvatar($id, $request);
            
            return response()->json([
                'status' => true,
                'user' => $user,
            ], 200);

        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Erro durante a remoção do avatar.',
            ], 404);
        }
    }
}
