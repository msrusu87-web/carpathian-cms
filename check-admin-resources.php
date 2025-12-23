#!/usr/bin/env php
<?php

/**
 * Admin Resources Verification Script
 * Checks if all multilingual resources are properly configured
 */

$checks = [];
$errors = [];

// Define the production path
$basePath = '/var/www/carphatian.ro/html';

echo "\n=== CHECKING ADMIN RESOURCES CONFIGURATION ===\n\n";

// 1. Check Page Resource
echo "1. Checking PageResource...\n";
$pageResource = file_get_contents("$basePath/app/Filament/Resources/PageResource.php");
$checks['page_translatable'] = strpos($pageResource, 'use Translatable;') !== false;
$checks['page_locales'] = strpos($pageResource, 'getTranslatableLocales') !== false;
$checks['page_tinyeditor'] = strpos($pageResource, 'TinyEditor::make') !== false;
$checks['page_profile'] = strpos($pageResource, "->profile('full')") !== false;

if (!$checks['page_translatable'] || !$checks['page_locales']) {
    $errors[] = "PageResource missing Translatable trait or getTranslatableLocales method";
}
echo "   Translatable: " . ($checks['page_translatable'] ? '✓' : '✗') . "\n";
echo "   Locales: " . ($checks['page_locales'] ? '✓' : '✗') . "\n";
echo "   TinyEditor: " . ($checks['page_tinyeditor'] ? '✓' : '✗') . "\n";
echo "   Full Profile: " . ($checks['page_profile'] ? '✓' : '✗') . "\n";

// 2. Check Product Resource
echo "\n2. Checking ProductResource...\n";
$productResource = file_get_contents("$basePath/app/Filament/Resources/ProductResource.php");
$checks['product_translatable'] = strpos($productResource, 'use Translatable;') !== false;
$checks['product_locales'] = strpos($productResource, 'getTranslatableLocales') !== false;
$checks['product_tinyeditor'] = strpos($productResource, 'TinyEditor::make') !== false;
$checks['product_profile'] = strpos($productResource, "->profile('full')") !== false;

if (!$checks['product_translatable'] || !$checks['product_locales']) {
    $errors[] = "ProductResource missing Translatable trait or getTranslatableLocales method";
}
echo "   Translatable: " . ($checks['product_translatable'] ? '✓' : '✗') . "\n";
echo "   Locales: " . ($checks['product_locales'] ? '✓' : '✗') . "\n";
echo "   TinyEditor: " . ($checks['product_tinyeditor'] ? '✓' : '✗') . "\n";
echo "   Full Profile: " . ($checks['product_profile'] ? '✓' : '✗') . "\n";

// 3. Check Post Resource
echo "\n3. Checking PostResource...\n";
$postResource = file_get_contents("$basePath/app/Filament/Resources/PostResource.php");
$checks['post_translatable'] = strpos($postResource, 'use Translatable;') !== false;
$checks['post_locales'] = strpos($postResource, 'getTranslatableLocales') !== false;
$checks['post_tinyeditor'] = strpos($postResource, 'TinyEditor::make') !== false;
$checks['post_profile'] = strpos($postResource, "->profile('full')") !== false;

if (!$checks['post_translatable'] || !$checks['post_locales']) {
    $errors[] = "PostResource missing Translatable trait or getTranslatableLocales method";
}
echo "   Translatable: " . ($checks['post_translatable'] ? '✓' : '✗') . "\n";
echo "   Locales: " . ($checks['post_locales'] ? '✓' : '✗') . "\n";
echo "   TinyEditor: " . ($checks['post_tinyeditor'] ? '✓' : '✗') . "\n";
echo "   Full Profile: " . ($checks['post_profile'] ? '✓' : '✗') . "\n";

// 4. Check Widget Resource
echo "\n4. Checking WidgetResource...\n";
$widgetResource = file_get_contents("$basePath/app/Filament/Resources/WidgetResource.php");
$checks['widget_translatable'] = strpos($widgetResource, 'use Translatable;') !== false;
$checks['widget_locales'] = strpos($widgetResource, 'getTranslatableLocales') !== false;
$checks['widget_tinyeditor'] = strpos($widgetResource, 'TinyEditor::make') !== false;
$checks['widget_section'] = strpos($widgetResource, 'Section::make') !== false;

if (!$checks['widget_translatable'] || !$checks['widget_locales']) {
    $errors[] = "WidgetResource missing Translatable trait or getTranslatableLocales method";
}
echo "   Translatable: " . ($checks['widget_translatable'] ? '✓' : '✗') . "\n";
echo "   Locales: " . ($checks['widget_locales'] ? '✓' : '✗') . "\n";
echo "   TinyEditor: " . ($checks['widget_tinyeditor'] ? '✓' : '✗') . "\n";
echo "   Sections: " . ($checks['widget_section'] ? '✓' : '✗') . "\n";

// 5. Check Models
echo "\n5. Checking Models...\n";
$pageModel = file_get_contents("$basePath/app/Models/Page.php");
$productModel = file_get_contents("$basePath/app/Models/Product.php");
$postModel = file_get_contents("$basePath/app/Models/Post.php");
$widgetModel = file_get_contents("$basePath/app/Models/Widget.php");

$checks['page_model_translatable'] = strpos($pageModel, 'use HasTranslations;') !== false;
$checks['product_model_translatable'] = strpos($productModel, 'use HasTranslations;') !== false;
$checks['post_model_translatable'] = strpos($postModel, 'use HasTranslations;') !== false;
$checks['widget_model_translatable'] = strpos($widgetModel, 'use HasTranslations;') !== false;

echo "   Page Model: " . ($checks['page_model_translatable'] ? '✓' : '✗') . "\n";
echo "   Product Model: " . ($checks['product_model_translatable'] ? '✓' : '✗') . "\n";
echo "   Post Model: " . ($checks['post_model_translatable'] ? '✓' : '✗') . "\n";
echo "   Widget Model: " . ($checks['widget_model_translatable'] ? '✓' : '✗') . "\n";

// 6. Check Resource Page Classes
echo "\n6. Checking Resource Page Classes...\n";
$pageEdit = file_get_contents("$basePath/app/Filament/Resources/PageResource/Pages/EditPage.php");
$productEdit = file_get_contents("$basePath/app/Filament/Resources/ProductResource/Pages/EditProduct.php");
$postEdit = file_get_contents("$basePath/app/Filament/Resources/PostResource/Pages/EditPost.php");
$widgetEdit = file_get_contents("$basePath/app/Filament/Resources/WidgetResource/Pages/EditWidget.php");

$checks['page_edit_translatable'] = strpos($pageEdit, 'use EditRecord\Concerns\Translatable;') !== false;
$checks['product_edit_translatable'] = strpos($productEdit, 'use EditRecord\Concerns\Translatable;') !== false;
$checks['post_edit_translatable'] = strpos($postEdit, 'use EditRecord\Concerns\Translatable;') !== false;
$checks['widget_edit_translatable'] = strpos($widgetEdit, 'use EditRecord\Concerns\Translatable;') !== false;

$checks['page_edit_switcher'] = strpos($pageEdit, 'LocaleSwitcher::make()') !== false;
$checks['product_edit_switcher'] = strpos($productEdit, 'LocaleSwitcher::make()') !== false;
$checks['post_edit_switcher'] = strpos($postEdit, 'LocaleSwitcher::make()') !== false;
$checks['widget_edit_switcher'] = strpos($widgetEdit, 'LocaleSwitcher::make()') !== false;

echo "   PageResource/Pages/EditPage: " . ($checks['page_edit_translatable'] && $checks['page_edit_switcher'] ? '✓' : '✗') . "\n";
echo "   ProductResource/Pages/EditProduct: " . ($checks['product_edit_translatable'] && $checks['product_edit_switcher'] ? '✓' : '✗') . "\n";
echo "   PostResource/Pages/EditPost: " . ($checks['post_edit_translatable'] && $checks['post_edit_switcher'] ? '✓' : '✗') . "\n";
echo "   WidgetResource/Pages/EditWidget: " . ($checks['widget_edit_translatable'] && $checks['widget_edit_switcher'] ? '✓' : '✗') . "\n";

// 7. Check AdminPanelProvider
echo "\n7. Checking AdminPanelProvider...\n";
if (file_exists("$basePath/app/Providers/Filament/AdminPanelProvider.php")) {
    $provider = file_get_contents("$basePath/app/Providers/Filament/AdminPanelProvider.php");
    $checks['provider_exists'] = true;
    $checks['provider_unsaved_alerts'] = strpos($provider, 'unsavedChangesAlerts()') !== false;
    echo "   Provider exists: ✓\n";
    echo "   Unsaved changes alerts: " . ($checks['provider_unsaved_alerts'] ? '✓' : '✗') . "\n";
} else {
    $checks['provider_exists'] = false;
    echo "   Provider exists: ✗\n";
}

// 8. Check CSS file
echo "\n8. Checking Custom CSS...\n";
$checks['css_exists'] = file_exists("$basePath/resources/css/admin-enhancements.css");
echo "   CSS file exists: " . ($checks['css_exists'] ? '✓' : '✗') . "\n";

// Summary
echo "\n=== SUMMARY ===\n";
$totalChecks = count($checks);
$passedChecks = count(array_filter($checks));
echo "Passed: $passedChecks / $totalChecks\n";

if (!empty($errors)) {
    echo "\n=== ERRORS FOUND ===\n";
    foreach ($errors as $error) {
        echo "  ✗ $error\n";
    }
    exit(1);
} else {
    echo "\n✓ All checks passed!\n\n";
    exit(0);
}
