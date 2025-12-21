<?php

/**
 * Custom Plugins Autoloader
 */

spl_autoload_register(function ($class) {
    // Check if it's a Plugins namespace
    if (strpos($class, 'Plugins\\') !== 0) {
        return;
    }
    
    // Convert namespace to file path
    $classPath = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $classPath = str_replace('Plugins' . DIRECTORY_SEPARATOR, '', $classPath);
    
    $basePath = dirname(__DIR__);
    $attempts = [
        $basePath . '/plugins/' . $classPath . '.php',
    ];
    
    foreach ($attempts as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
