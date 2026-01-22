<?php

// Test script to force-register resources and check navigation
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Manual Resource Registration Test ===\n";

try {
    // Get the default Filament panel
    $panel = \Filament\Facades\Filament::getDefaultPanel();
    echo "✅ Panel loaded: " . $panel->getId() . "\n";
    
    // Get currently registered resources
    $resources = $panel->getResources();
    echo "📋 Currently registered resources: " . count($resources) . "\n";
    
    // Look for our test resource
    $found = false;
    foreach ($resources as $resource) {
        if (str_contains($resource, 'TestMarketing')) {
            echo "✅ Found TestMarketingResource: $resource\n";
            $found = true;
        }
        if (str_contains($resource, 'MarketingContact')) {
            echo "✅ Found MarketingContactResource: $resource\n";
            $found = true;
        }
    }
    
    if (!$found) {
        echo "❌ No marketing resources found in panel registration\n";
        echo "🔧 Trying to manually register...\n";
        
        // Check if classes exist
        if (class_exists('App\Filament\Resources\TestMarketingResource')) {
            echo "✅ TestMarketingResource class exists\n";
        } else {
            echo "❌ TestMarketingResource class not found\n";
        }
    }
    
    // Test the navigation item creation
    echo "\n🧪 Testing navigation generation...\n";
    if (class_exists('App\Filament\Resources\TestMarketingResource')) {
        $resource = 'App\Filament\Resources\TestMarketingResource';
        echo "Navigation Label: " . $resource::getNavigationLabel() . "\n";
        echo "Navigation Icon: " . $resource::getNavigationIcon() . "\n";
        echo "Can Access: " . ($resource::canAccess() ? 'Yes' : 'No') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}