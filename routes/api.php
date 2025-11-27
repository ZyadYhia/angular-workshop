<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Api\CatController;
use App\Http\Controllers\Api\ExamController;
use App\Http\Controllers\Api\SkillController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::put('profile', [AuthController::class, 'updateProfile']);
        Route::put('password', [AuthController::class, 'updatePassword']);
    });
});

Route::apiResource('categories', CatController::class)->only(['index', 'show']);
Route::apiResource('categories', CatController::class)->only(['store', 'update', 'destroy'])->middleware('auth:api');

Route::apiResource('skills', SkillController::class)->only(['index', 'show']);
Route::apiResource('skills', SkillController::class)->only(['store', 'update', 'destroy'])->middleware('auth:api');

Route::apiResource('exams', ExamController::class)->only(['index', 'show']);
Route::apiResource('exams', ExamController::class)->only(['store', 'update', 'destroy'])->middleware('auth:api');

Route::middleware(['auth:api'])->group(function () {
    Route::get('exams/show-questions/{id}', [ExamController::class, 'showQuestions']);
    Route::post('exams/start/{id}', [ExamController::class, 'start']);
    Route::post('exams/submit/{id}', [ExamController::class, 'submit']);
});
