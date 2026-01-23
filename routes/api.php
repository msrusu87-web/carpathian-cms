<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AiTemplateController;
use App\Http\Controllers\Api\AiPluginController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\JobStatusController;
use Plugins\Marketing\Http\Controllers\BrevoWebhookController;
use Plugins\Marketing\Http\Controllers\MarketingApiController;

/*
|--------------------------------------------------------------------------
| API Routes v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {
    
    // Public health check
    Route::get('/health', fn() => response()->json(['status' => 'ok', 'timestamp' => now()->toIso8601String()]));
    
    // Authenticated routes
    Route::middleware('auth:sanctum')->group(function () {
        
        // Posts
        Route::get('/posts', [PostController::class, 'index']);
        
        // Products CRUD
        Route::apiResource('products', ProductController::class);
        Route::post('/products/import', [ProductController::class, 'import']);
        Route::post('/products/bulk-update', [ProductController::class, 'bulkUpdate']);
        
        // Pages CRUD
        Route::apiResource('pages', PageController::class);
        
        // Backups (admin-only via policy)
        Route::prefix('backups')->group(function () {
            Route::get('/', [BackupController::class, 'index']);
            Route::post('/run', [BackupController::class, 'run']);
            Route::post('/restore', [BackupController::class, 'restore']);
            Route::post('/restore-latest', [BackupController::class, 'restoreLatest']);
            Route::delete('/', [BackupController::class, 'destroy']);
        });
        
        // Job status polling
        Route::get('/jobs/{jobId}', [JobStatusController::class, 'show']);
        
        // AI Generation
        Route::prefix('ai')->group(function () {
            Route::post('/templates/generate', [AiTemplateController::class, 'generate']);
            Route::post('/templates/blocks/generate', [AiTemplateController::class, 'generateBlock']);
            Route::post('/plugins/generate', [AiPluginController::class, 'generate']);
            Route::post('/plugins/{plugin}/improve', [AiPluginController::class, 'improve']);
        });
        
        // Marketing
        Route::prefix('marketing')->group(function () {
            Route::post('/contacts/import', [MarketingApiController::class, 'import']);
            Route::get('/campaign/{campaignId}/stats', [MarketingApiController::class, 'campaignStats']);
        });
    });
});

// Legacy routes (for backwards compatibility)
Route::middleware('auth:sanctum')->get('/posts', [PostController::class, 'index']);

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
