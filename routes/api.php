<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CertificationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PortfolioProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\StorageAuditController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/health', fn () => response()->json(['status' => 'ok']));

Route::post('/login', [AuthController::class, 'login']);
Route::post('/contact', [MessageController::class, 'store']);

Route::get('/profile', [PortfolioProfileController::class, 'show']);
Route::get('/skills', [SkillController::class, 'index']);
Route::get('/skills/{skill}', [SkillController::class, 'show']);
Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/{project}', [ProjectController::class, 'show']);
Route::get('/experiences', [ExperienceController::class, 'index']);
Route::get('/experiences/{experience}', [ExperienceController::class, 'show']);
Route::get('/certifications', [CertificationController::class, 'index']);
Route::get('/certifications/{certification}', [CertificationController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/messages', [MessageController::class, 'index']);
    Route::get('/storage-audit', StorageAuditController::class);
    Route::get('/messages/{message}', [MessageController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
    Route::put('/profile', [PortfolioProfileController::class, 'update']);

    Route::post('/skills', [SkillController::class, 'store']);
    Route::put('/skills/{skill}', [SkillController::class, 'update']);
    Route::delete('/skills/{skill}', [SkillController::class, 'destroy']);

    Route::post('/projects', [ProjectController::class, 'store']);
    Route::put('/projects/{project}', [ProjectController::class, 'update']);
    Route::delete('/projects/{project}/images/{image}', [ProjectController::class, 'destroyImage']);
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy']);

    Route::post('/experiences', [ExperienceController::class, 'store']);
    Route::put('/experiences/{experience}', [ExperienceController::class, 'update']);
    Route::delete('/experiences/{experience}', [ExperienceController::class, 'destroy']);

    Route::post('/certifications', [CertificationController::class, 'store']);
    Route::put('/certifications/{certification}', [CertificationController::class, 'update']);
    Route::delete('/certifications/{certification}', [CertificationController::class, 'destroy']);
    Route::delete('/messages/{message}', [MessageController::class, 'destroy']);
});
