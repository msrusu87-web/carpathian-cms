#!/usr/bin/env php
<?php

/**
 * Translate Admin Strings to Romanian
 * Takes extracted strings and creates proper Romanian translations
 */

echo "🇷🇴 TRANSLATING TO ROMANIAN\n";
echo "============================\n\n";

// Load strings that need translation
$needsFile = '/home/ubuntu/carpathian-cms/needs-translation.json';
if (!file_exists($needsFile)) {
    die("❌ File not found: $needsFile\n");
}

$needsTranslation = json_decode(file_get_contents($needsFile), true);
echo "📝 Loaded " . count($needsTranslation) . " strings to translate\n\n";

// Romanian translations dictionary (common admin terms)
$translationDict = [
    // Database fields
    'name' => 'Nume',
    'slug' => 'Slug',
    'created_at' => 'Creat La',
    'updated_at' => 'Actualizat La',
    'deleted_at' => 'Șters La',
    'is_active' => 'Activ',
    'description' => 'Descriere',
    'status' => 'Status',
    'title' => 'Titlu',
    'order' => 'Ordine',
    'type' => 'Tip',
    'user_id' => 'ID Utilizator',
    'email' => 'Email',
    'phone' => 'Telefon',
    'value' => 'Valoare',
    'key' => 'Cheie',
    'price' => 'Preț',
    'category_id' => 'ID Categorie',
    'subject' => 'Subiect',
    'guard_name' => 'Nume Gardă',
    'rating' => 'Evaluare',
    'company_name' => 'Nume Companie',
    'model' => 'Model',
    'code' => 'Cod',
    'category' => 'Categorie',
    'stock' => 'Stoc',
    'version' => 'Versiune',
    'meta_title' => 'Titlu Meta',
    'meta_description' => 'Descriere Meta',
    'meta_keywords' => 'Cuvinte Cheie Meta',
    'is_featured' => 'Recomandat',
    'city' => 'Oraș',
    'company' => 'Companie',
    'provider' => 'Furnizor',
    'is_default' => 'Implicit',
    'notes' => 'Notițe',
    'content' => 'Conținut',
    'image' => 'Imagine',
    'icon' => 'Iconiță',
    'color' => 'Culoare',
    'url' => 'URL',
    'link' => 'Link',
    'address' => 'Adresă',
    'country' => 'Țară',
    'state' => 'Județ',
    'zip' => 'Cod Poștal',
    'message' => 'Mesaj',
    'author' => 'Autor',
    'published_at' => 'Publicat La',
    'expires_at' => 'Expiră La',
    'is_published' => 'Publicat',
    'is_visible' => 'Vizibil',
    'quantity' => 'Cantitate',
    'discount' => 'Reducere',
    'tax' => 'TVA',
    'total' => 'Total',
    'subtotal' => 'Subtotal',
    'date' => 'Dată',
    'time' => 'Oră',
    'username' => 'Nume Utilizator',
    'password' => 'Parolă',
    'permissions' => 'Permisiuni',
    'roles' => 'Roluri',
    'role' => 'Rol',
    'id' => 'ID',
    'parent_id' => 'ID Părinte',
    'position' => 'Poziție',
    'priority' => 'Prioritate',
    'tags' => 'Etichete',
    'comment' => 'Comentariu',
    'comments' => 'Comentarii',
    'views' => 'Vizualizări',
    'downloads' => 'Descărcări',
    'likes' => 'Aprecieri',
    
    // Common labels
    'active' => 'Activ',
    'inactive' => 'Inactiv',
    'all' => 'Toate',
    'published' => 'Publicat',
    'draft' => 'Ciornă',
    'pending' => 'În Așteptare',
    'approved' => 'Aprobat',
    'rejected' => 'Respins',
    'completed' => 'Finalizat',
    'cancelled' => 'Anulat',
    'processing' => 'În Procesare',
    'failed' => 'Eșuat',
    'success' => 'Succes',
    'error' => 'Eroare',
    'warning' => 'Avertisment',
    'info' => 'Informație',
    'yes' => 'Da',
    'no' => 'Nu',
    'true' => 'Adevărat',
    'false' => 'Fals',
    'enabled' => 'Activat',
    'disabled' => 'Dezactivat',
    'public' => 'Public',
    'private' => 'Privat',
    'visible' => 'Vizibil',
    'hidden' => 'Ascuns',
    'required' => 'Obligatoriu',
    'optional' => 'Opțional',
    
    // Helper text
    'url_friendly_identifier' => 'Identificator prietenos URL',
    'lower_numbers_appear_first' => 'Numerele mai mici apar primele',
    'select_an_option' => 'Selectează o opțiune',
    'enter_text_here' => 'Introdu text aici',
    'choose_file' => 'Alege fișier',
    'upload_image' => 'Încarcă imagine',
    'select_date' => 'Selectează dată',
    'select_time' => 'Selectează oră',
    
    // User related
    'user' => 'Utilizator',
    'users' => 'Utilizatori',
    'profile' => 'Profil',
    'account' => 'Cont',
    'login' => 'Autentificare',
    'logout' => 'Deconectare',
    'register' => 'Înregistrare',
    'remember_me' => 'Ține-mă Minte',
    'forgot_password' => 'Ai Uitat Parola',
    'reset_password' => 'Resetează Parola',
    
    // Relations
    'user_name' => 'Nume Utilizator',
    'category_name' => 'Nume Categorie',
    'product_name' => 'Nume Produs',
];

// Generate translations
$translations = [];
$count = 0;

foreach ($needsTranslation as $key => $originalString) {
    // Check if we have a direct translation
    $lowerKey = strtolower($key);
    $lowerOriginal = strtolower($originalString);
    
    if (isset($translationDict[$lowerKey])) {
        $translations[$key] = $translationDict[$lowerKey];
        $count++;
    } elseif (isset($translationDict[$lowerOriginal])) {
        $translations[$key] = $translationDict[$lowerOriginal];
        $count++;
    } else {
        // Try to translate by pattern
        if (strpos($key, '_') !== false) {
            // Split and translate parts
            $parts = explode('_', $key);
            $translatedParts = [];
            foreach ($parts as $part) {
                if (isset($translationDict[$part])) {
                    $translatedParts[] = $translationDict[$part];
                } else {
                    $translatedParts[] = ucfirst($part);
                }
            }
            $translations[$key] = implode(' ', $translatedParts);
            $count++;
        } else {
            // Keep original as fallback, capitalize properly
            $translations[$key] = ucwords(str_replace('_', ' ', $originalString));
        }
    }
    
    echo sprintf("%4d. %s => %s\n", $count, $key, $translations[$key]);
}

echo "\n📊 TRANSLATION SUMMARY\n";
echo "======================\n";
echo "Translated: $count strings\n\n";

// Load existing Romanian translations
$roFile = '/var/www/carphatian.ro/html/lang/ro/messages.php';
$existingTranslations = include $roFile;

// Merge with new translations
$allTranslations = array_merge($existingTranslations, $translations);
ksort($allTranslations);

echo "Total Romanian keys: " . count($allTranslations) . "\n\n";

// Save to file
$output = "<?php\n\nreturn [\n";
foreach ($allTranslations as $key => $value) {
    $key = addslashes($key);
    $value = addslashes($value);
    $output .= "    '$key' => '$value',\n";
}
$output .= "];\n";

$backupFile = '/home/ubuntu/carpathian-cms/lang-ro-messages-backup-' . date('YmdHis') . '.php';
copy($roFile, $backupFile);
echo "💾 Backup saved to: $backupFile\n";

file_put_contents($roFile, $output);
echo "✅ Updated: $roFile\n";

// Copy to git repo
$gitFile = '/home/ubuntu/carpathian-cms/lang/ro/messages.php';
copy($roFile, $gitFile);
echo "✅ Copied to git: $gitFile\n";

echo "\n🎉 ROMANIAN TRANSLATION COMPLETE!\n";
echo "Now run: php translate-to-all-languages.php\n";
