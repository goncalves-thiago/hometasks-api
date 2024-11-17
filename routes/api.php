<?php

use App\Http\Controllers\Api\FamilyController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\TaskController;


// Public route
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/users', [UserController::class, 'store']);

// Restrict route
Route::group(['middleware' => ['auth:sanctum']], function () {
    
    // Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::get('/user', [UserController::class, 'getUserData']);
    Route::get('/users/{user}/allowanceToReceive/{month}', [UserController::class, 'getAllowanceToReceive']);
    Route::get('/users/invite/{query}', [UserController::class, 'getUsersWithoutFamily']);
    Route::post('/users/{user}/addAvatar', [UserController::class, 'addAvatar']);
    Route::post('/users/{user}/removeAvatar', [UserController::class, 'removeAvatar']);

    // Family
    Route::get('/families', [FamilyController::class, 'index']);
    Route::get('/families/{family}', [FamilyController::class, 'show']);
    Route::post('families', [FamilyController::class, 'store']);
    Route::put('/families/{family}', [FamilyController::class, 'update']);
    Route::delete('/families/{family}', [FamilyController::class, 'destroy']);
    Route::post('/families/{family}/addMember', [FamilyController::class, 'addMember']);
    Route::post('/families/{family}/removeMember', [FamilyController::class, 'removeMember']);
    Route::get('/families/{family}/tasks', [FamilyController::class, 'getFamilyTasks']);
    Route::get('/families/{family}/dashboard/{month}', [FamilyController::class, 'getTasksDashboardByMonth']);
    Route::get('/families/{family}/users', [FamilyController::class, 'getFamilyUsers']);
    Route::get('/families/{family}/report/{month}', [FamilyController::class, 'getReportByMonth']);

    //Task
    Route::get('/tasks', [TaskController::class, 'index']);
    Route::get('/tasks/{task}', [TaskController::class, 'show']);
    Route::post('/tasks', [TaskController::class, 'store']);
    Route::post('/tasks/{task}/addAttachment', [TaskController::class, 'addAttachment']);
    Route::post('/tasks/{task}/removeAttachment', [TaskController::class, 'removeAttachment']);
    Route::put('/tasks/{task}', [TaskController::class, 'update']);
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
    Route::get('/tasks/dashboard/{family}', [TaskController::class, 'getTasksHistory']);

    //Logout
    Route::post('/logout/{user}', [LoginController::class, 'logout']);
});

// Route fallback
Route::fallback(function(){
    return response()->json([
        'status' => false,
        'message' => 'Objeto não encontrado!',
    ], 404);
});
