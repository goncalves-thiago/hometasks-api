<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Models\Family;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request): JsonResponse
    {

        // Validate user and password
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {

            // Get user data
            $user = Auth::user();

            // Get user token
            $token = $request->user()->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'token' => $token,
                'user' => $user,
            ], 201);    
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Login ou senha incorreta'
            ], 401);
        }
    }

    public function logout(User $user): JsonResponse
    {

        try {

            // Clear user token
            $user->tokens()->delete();

            return response()->json([
                'status' => true,
                'message' => 'Deslogado com sucesso.'
            ], 200);
        } catch (Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Não foi possível deslogar.',
            ], 400);
        }
    }
}
