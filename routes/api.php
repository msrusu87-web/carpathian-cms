<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiTemplateController;
use App\Http\Controllers\Api\AiPluginController;
use App\Http\Controllers\Api\PostController;

Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'index']);

// AI Template Generation Routes
Route::prefix('ai')->middleware('auth:sanctum')->group(function () {
    Route::post('/templates/generate', [AiTemplateController::class, 'generate']);
    Route::post('/templates/blocks/generate', [AiTemplateController::class, 'generateBlock']);
    Route::post('/plugins/generate', [AiPluginController::class, 'generate']);
    Route::post('/plugins/{plugin}/improve', [AiPluginController::class, 'improve']);
});
