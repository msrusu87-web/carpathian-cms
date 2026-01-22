<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiTemplateController;
use App\Http\Controllers\Api\AiPluginController;
use App\Http\Controllers\Api\PostController;
use Plugins\Marketing\Http\Controllers\BrevoWebhookController;
use Plugins\Marketing\Http\Controllers\MarketingApiController;

Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'index']);

// AI Template Generation Routes
Route::prefix('ai')->middleware('auth:sanctum')->group(function () {
    Route::post('/templates/generate', [AiTemplateController::class, 'generate']);
    Route::post('/templates/blocks/generate', [AiTemplateController::class, 'generateBlock']);
    Route::post('/plugins/generate', [AiPluginController::class, 'generate']);
    Route::post('/plugins/{plugin}/improve', [AiPluginController::class, 'improve']);
});

// Marketing API Routes
Route::prefix('marketing')->group(function () {
    // Webhook endpoint (no auth required - Brevo will call this)
    Route::post('/webhook/brevo', [BrevoWebhookController::class, 'handle']);
    
    // Public endpoints
    Route::get('/unsubscribe/{token}', [MarketingApiController::class, 'unsubscribe']);
    Route::get('/track/{trackingId}', [MarketingApiController::class, 'trackOpen']);
    
    // Authenticated endpoints
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/contacts/import', [MarketingApiController::class, 'import']);
        Route::get('/campaign/{campaignId}/stats', [MarketingApiController::class, 'campaignStats']);
    });
});
