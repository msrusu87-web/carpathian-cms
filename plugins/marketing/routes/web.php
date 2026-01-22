<?php

use Illuminate\Support\Facades\Route;
use Plugins\Marketing\Http\Controllers\MarketingApiController;

/*
|--------------------------------------------------------------------------
| Marketing Plugin Routes
|--------------------------------------------------------------------------
*/

// Public routes (no auth required for unsubscribe/tracking)
Route::prefix('api/marketing')->group(function () {
    Route::get('/unsubscribe/{token}', [MarketingApiController::class, 'unsubscribe'])
        ->name('marketing.unsubscribe');
    
    Route::get('/track/{trackingId}', [MarketingApiController::class, 'trackOpen'])
        ->name('marketing.track.open');
    
    Route::get('/click/{trackingId}', [MarketingApiController::class, 'trackClick'])
        ->name('marketing.track.click');
});

// Protected API routes
Route::prefix('api/marketing')->middleware(['auth:sanctum'])->group(function () {
    Route::get('/campaigns/{campaignId}/stats', [MarketingApiController::class, 'campaignStats'])
        ->name('marketing.api.campaign.stats');
    
    Route::get('/rate-limits', [MarketingApiController::class, 'rateLimits'])
        ->name('marketing.api.rate-limits');
});
