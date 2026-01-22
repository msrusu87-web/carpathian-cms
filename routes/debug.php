<?php
// Create a simple test endpoint to verify our marketing resources work
use Illuminate\Support\Facades\Route;

Route::get('/test-marketing-debug', function () {
    $output = [];
    
    try {
        $output['panel'] = app(\Filament\PanelManager::class)->getDefaultPanel()->getId();
        $output['resources'] = collect(app(\Filament\PanelManager::class)->getDefaultPanel()->getResources())
            ->filter(fn($resource) => str_contains($resource, 'Marketing'))
            ->values()
            ->toArray();
        
        $output['marketing_contacts'] = \Plugins\Marketing\Models\MarketingContact::count();
        $output['routes'] = collect(\Illuminate\Support\Facades\Route::getRoutes())
            ->map(fn($route) => $route->uri)
            ->filter(fn($uri) => str_contains($uri, 'marketing'))
            ->values()
            ->toArray();
            
        $output['status'] = 'SUCCESS';
    } catch (Exception $e) {
        $output['error'] = $e->getMessage();
        $output['status'] = 'ERROR';
    }
    
    return response()->json($output);
});