#!/usr/bin/env php
<?php

/**
 * Add missing 'view' and 'actions' translations
 */

$translations = [
    'ro' => ['view' => 'Vizualizează', 'actions' => 'Acțiuni'],
    'en' => ['view' => 'View', 'actions' => 'Actions'],
    'de' => ['view' => 'Ansehen', 'actions' => 'Aktionen'],
    'es' => ['view' => 'Ver', 'actions' => 'Acciones'],
    'fr' => ['view' => 'Voir', 'actions' => 'Actions'],
    'it' => ['view' => 'Visualizza', 'actions' => 'Azioni'],
];

foreach ($translations as $lang => $newKeys) {
    $file = "/home/ubuntu/carpathian-cms/lang/$lang/messages.php";
    $existing = include $file;
    
    foreach ($newKeys as $key => $value) {
        $existing[$key] = $value;
    }
    
    ksort($existing);
    
    $output = "<?php\n\nreturn [\n";
    foreach ($existing as $k => $v) {
        $k = addslashes($k);
        $v = addslashes($v);
        $output .= "    '$k' => '$v',\n";
    }
    $output .= "];\n";
    
    file_put_contents($file, $output);
    echo "$lang: " . count($existing) . " keys\n";
}

echo "\n✅ Added 'view' and 'actions' to all languages\n";
