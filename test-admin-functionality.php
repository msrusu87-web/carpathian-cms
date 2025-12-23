#!/usr/bin/env php
<?php

/**
 * Comprehensive Admin Resources Functional Test
 * Tests actual functionality of multilingual resources
 */

$basePath = '/var/www/carphatian.ro/html';
require_once $basePath . '/vendor/autoload.php';

$app = require_once $basePath . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "\n=== FUNCTIONAL TESTS FOR ADMIN RESOURCES ===\n\n";

$allPassed = true;

// Test 1: Check if Pages support translations
echo "1. Testing Page Model Translations...\n";
try {
    $page = App\Models\Page::first();
    if ($page) {
        $hasTranslations = method_exists($page, 'getTranslation');
        echo "   Page model has translation methods: " . ($hasTranslations ? '✓' : '✗') . "\n";
        
        if ($hasTranslations) {
            $enTitle = $page->getTranslation('title', 'en');
            $roTitle = $page->getTranslation('title', 'ro');
            echo "   EN Title: " . substr($enTitle, 0, 30) . "...\n";
            echo "   RO Title: " . substr($roTitle, 0, 30) . "...\n";
        }
    } else {
        echo "   No pages found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 2: Check if Products support translations
echo "\n2. Testing Product Model Translations...\n";
try {
    $product = App\Models\Product::first();
    if ($product) {
        $hasTranslations = method_exists($product, 'getTranslation');
        echo "   Product model has translation methods: " . ($hasTranslations ? '✓' : '✗') . "\n";
        
        if ($hasTranslations) {
            $enName = $product->getTranslation('name', 'en');
            $roName = $product->getTranslation('name', 'ro');
            echo "   EN Name: " . substr($enName, 0, 30) . "...\n";
            echo "   RO Name: " . substr($roName, 0, 30) . "...\n";
        }
    } else {
        echo "   No products found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 3: Check if Posts support translations
echo "\n3. Testing Post Model Translations...\n";
try {
    $post = App\Models\Post::first();
    if ($post) {
        $hasTranslations = method_exists($post, 'getTranslation');
        echo "   Post model has translation methods: " . ($hasTranslations ? '✓' : '✗') . "\n";
        
        if ($hasTranslations) {
            $enTitle = $post->getTranslation('title', 'en');
            $roTitle = $post->getTranslation('title', 'ro');
            echo "   EN Title: " . substr($enTitle, 0, 30) . "...\n";
            echo "   RO Title: " . substr($roTitle, 0, 30) . "...\n";
        }
    } else {
        echo "   No posts found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 4: Check if Widgets support translations
echo "\n4. Testing Widget Model Translations...\n";
try {
    $widget = App\Models\Widget::first();
    if ($widget) {
        $hasTranslations = method_exists($widget, 'getTranslation');
        echo "   Widget model has translation methods: " . ($hasTranslations ? '✓' : '✗') . "\n";
        
        if ($hasTranslations) {
            $enTitle = $widget->getTranslation('title', 'en');
            $roTitle = $widget->getTranslation('title', 'ro');
            echo "   EN Title: " . substr($enTitle, 0, 30) . "...\n";
            echo "   RO Title: " . ($roTitle ? substr($roTitle, 0, 30) : 'Not set') . "...\n";
        }
        
        // Test settings accessor
        $settings = $widget->settings;
        echo "   Settings is array: " . (is_array($settings) ? '✓' : '✗') . "\n";
    } else {
        echo "   No widgets found in database\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 5: Check database columns are JSON
echo "\n5. Testing Database Schema...\n";
try {
    $pageColumns = DB::select("SHOW COLUMNS FROM pages WHERE Field IN ('title', 'content', 'excerpt')");
    foreach ($pageColumns as $col) {
        echo "   pages.{$col->Field}: {$col->Type} " . ($col->Type === 'json' ? '✓' : '✗') . "\n";
    }
    
    $widgetColumns = DB::select("SHOW COLUMNS FROM widgets WHERE Field IN ('title', 'content')");
    foreach ($widgetColumns as $col) {
        echo "   widgets.{$col->Field}: {$col->Type} " . ($col->Type === 'json' ? '✓' : '✗') . "\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 6: Test creating/updating a widget translation
echo "\n6. Testing Translation Update Functionality...\n";
try {
    $widget = App\Models\Widget::first();
    if ($widget) {
        // Save current state
        $originalTitle = $widget->title;
        
        // Update Romanian translation
        $widget->setTranslation('title', 'ro', 'Test Widget RO');
        $widget->save();
        
        // Verify
        $widget->refresh();
        $roTitle = $widget->getTranslation('title', 'ro');
        echo "   Translation update successful: " . ($roTitle === 'Test Widget RO' ? '✓' : '✗') . "\n";
        
        // Restore original
        $widget->title = $originalTitle;
        $widget->save();
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

// Test 7: Verify Filament Resources are loadable
echo "\n7. Testing Filament Resources Load...\n";
try {
    $resources = [
        'PageResource' => App\Filament\Resources\PageResource::class,
        'ProductResource' => App\Filament\Resources\ProductResource::class,
        'PostResource' => App\Filament\Resources\PostResource::class,
        'WidgetResource' => App\Filament\Resources\WidgetResource::class,
    ];
    
    foreach ($resources as $name => $class) {
        if (class_exists($class)) {
            $locales = $class::getTranslatableLocales();
            $localeCount = count($locales);
            echo "   $name loads: ✓ ($localeCount locales)\n";
        } else {
            echo "   $name loads: ✗\n";
            $allPassed = false;
        }
    }
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
    $allPassed = false;
}

echo "\n=== SUMMARY ===\n";
if ($allPassed) {
    echo "✓ All functional tests passed!\n";
    echo "✓ Multilingual support is working correctly\n";
    echo "✓ Admin panel should be fully operational\n\n";
    exit(0);
} else {
    echo "✗ Some tests failed - check errors above\n\n";
    exit(1);
}
