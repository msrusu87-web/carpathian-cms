<?php

/**
 * Generate Romanian Admin Translations
 * Creates comprehensive Romanian translations for admin panel
 */

// Load extracted strings
$extracted = json_decode(file_get_contents(__DIR__ . '/admin-translations-to-add.json'), true);

// Romanian translations for admin panel
$adminTranslations = [
    // Technical / System
    'API' => 'API',
    'API Key' => 'Cheie API',
    '.env not found' => '.env nu a fost găsit',
    '1.0.0' => '1.0.0',
    
    // Status & Actions
    'Active' => 'Activ',
    'Activ' => 'Activ',
    'Activate' => 'Activează',
    'Activate Selected' => 'Activează Selecția',
    'Active Categories' => 'Categorii Active',
    'Active Only' => 'Doar Active',
    'Active Status' => 'Status Activ',
    'Inactive' => 'Inactiv',
    'Deactivate' => 'Dezactivează',
    'Enabled' => 'Activat',
    'Disabled' => 'Dezactivat',
    'Published' => 'Publicat',
    'Draft' => 'Ciornă',
    'Pending' => 'În așteptare',
    'Approved' => 'Aprobat',
    'Rejected' => 'Respins',
    'Completed' => 'Finalizat',
    'Processing' => 'Se procesează',
    'Failed' => 'Eșuat',
    'Cancelled' => 'Anulat',
    
    // Clusters/Navigation
    'Content' => 'Conținut',
    'AI' => 'AI',
    'Blog' => 'Blog',
    'Shop' => 'Magazin',
    'CMS' => 'CMS',
    'Communications' => 'Comunicații',
    'Design' => 'Design',
    'Settings' => 'Setări',
    'Users & Permissions' => 'Utilizatori & Permisiuni',
    
    // AI Related
    'AI Platform' => 'Platformă AI',
    'AI Powered' => 'Bazat pe AI',
    'AI Generation' => 'Generare AI',
    'AI Content' => 'Conținut AI',
    'AI Settings' => 'Setări AI',
    'Generate' => 'Generează',
    'Generate Content' => 'Generează Conținut',
    'Generated Content' => 'Conținut Generat',
    'Model' => 'Model',
    'Provider' => 'Furnizor',
    'Temperature' => 'Temperatură',
    'Max Tokens' => 'Token-uri Maxime',
    'Prompt' => 'Prompt',
    'Response' => 'Răspuns',
    
    // Word counts
    '300-500 words' => '300-500 cuvinte',
    '500-1000 words' => '500-1000 cuvinte',
    '1000-2000 words' => '1000-2000 cuvinte',
    
    // Pages/Posts
    'Page' => 'Pagină',
    'Pages' => 'Pagini',
    'Post' => 'Articol',
    'Posts' => 'Articole',
    'Title' => 'Titlu',
    'Slug' => 'Slug',
    'Content' => 'Conținut',
    'Excerpt' => 'Extras',
    'Featured Image' => 'Imagine Principală',
    'Author' => 'Autor',
    'Status' => 'Status',
    'Publish Date' => 'Data Publicării',
    'Published Date' => 'Data Publicării',
    'Created Date' => 'Data Creării',
    'Updated Date' => 'Data Actualizării',
    'Meta Title' => 'Meta Titlu',
    'Meta Description' => 'Meta Descriere',
    'Meta Keywords' => 'Meta Cuvinte Cheie',
    
    // Categories & Tags
    'Category' => 'Categorie',
    'Categories' => 'Categorii',
    'Tag' => 'Etichetă',
    'Tags' => 'Etichete',
    'Name' => 'Nume',
    'Description' => 'Descriere',
    'Parent Category' => 'Categorie Părinte',
    'Icon' => 'Icoană',
    'Color' => 'Culoare',
    'Order' => 'Ordine',
    
    // Products & E-commerce
    'Product' => 'Produs',
    'Products' => 'Produse',
    'Price' => 'Preț',
    'Regular Price' => 'Preț Obișnuit',
    'Sale Price' => 'Preț Redus',
    'SKU' => 'SKU',
    'Stock' => 'Stoc',
    'In Stock' => 'În Stoc',
    'Out of Stock' => 'Stoc Epuizat',
    'Stock Quantity' => 'Cantitate Stoc',
    'Manage Stock' => 'Gestionează Stoc',
    'Stock Status' => 'Status Stoc',
    'Featured Product' => 'Produs Recomandat',
    'Best Seller' => 'Cel Mai Vândut',
    'New Arrival' => 'Nou Sosit',
    'On Sale' => 'La Reducere',
    'Discount' => 'Reducere',
    'Discount Type' => 'Tip Reducere',
    'Discount Value' => 'Valoare Reducere',
    
    // Orders
    'Order' => 'Comandă',
    'Orders' => 'Comenzi',
    'Order Number' => 'Număr Comandă',
    'Order Date' => 'Data Comenzii',
    'Order Status' => 'Status Comandă',
    'Order Total' => 'Total Comandă',
    'Customer' => 'Client',
    'Customer Name' => 'Nume Client',
    'Customer Email' => 'Email Client',
    'Billing Address' => 'Adresă Facturare',
    'Shipping Address' => 'Adresă Livrare',
    'Shipping Method' => 'Metodă Livrare',
    'Payment Method' => 'Metodă Plată',
    'Payment Status' => 'Status Plată',
    'Subtotal' => 'Subtotal',
    'Tax' => 'Taxe',
    'Total' => 'Total',
    'Notes' => 'Notițe',
    
    // Users & Roles
    'User' => 'Utilizator',
    'Users' => 'Utilizatori',
    'Role' => 'Rol',
    'Roles' => 'Roluri',
    'Permission' => 'Permisiune',
    'Permissions' => 'Permisiuni',
    'Email' => 'Email',
    'Password' => 'Parolă',
    'Confirm Password' => 'Confirmă Parola',
    'First Name' => 'Prenume',
    'Last Name' => 'Nume',
    'Full Name' => 'Nume Complet',
    'Phone' => 'Telefon',
    'Address' => 'Adresă',
    'City' => 'Oraș',
    'State' => 'Județ',
    'Country' => 'Țară',
    'Postal Code' => 'Cod Poștal',
    'Avatar' => 'Avatar',
    'Bio' => 'Biografie',
    
    // Media & Files
    'Media' => 'Media',
    'Image' => 'Imagine',
    'Images' => 'Imagini',
    'File' => 'Fișier',
    'Files' => 'Fișiere',
    'Upload' => 'Încarcă',
    'Upload File' => 'Încarcă Fișier',
    'Choose File' => 'Alege Fișier',
    'File Name' => 'Nume Fișier',
    'File Size' => 'Dimensiune Fișier',
    'File Type' => 'Tip Fișier',
    'Download' => 'Descarcă',
    'Delete' => 'Șterge',
    'Alt Text' => 'Text Alternativ',
    'Caption' => 'Legendă',
    
    // Menus & Navigation
    'Menu' => 'Meniu',
    'Menus' => 'Meniuri',
    'Menu Item' => 'Element Meniu',
    'Menu Items' => 'Elemente Meniu',
    'Menu Location' => 'Locație Meniu',
    'Parent Menu' => 'Meniu Părinte',
    'Link' => 'Link',
    'URL' => 'URL',
    'Target' => 'Țintă',
    'Open in New Tab' => 'Deschide în Tab Nou',
    'CSS Class' => 'Clasă CSS',
    'Position' => 'Poziție',
    
    // Forms & Fields
    'Form' => 'Formular',
    'Field' => 'Câmp',
    'Label' => 'Etichetă',
    'Placeholder' => 'Placeholder',
    'Helper Text' => 'Text Ajutător',
    'Default Value' => 'Valoare Implicită',
    'Required' => 'Obligatoriu',
    'Optional' => 'Opțional',
    'Validation' => 'Validare',
    'Min Length' => 'Lungime Minimă',
    'Max Length' => 'Lungime Maximă',
    'Min Value' => 'Valoare Minimă',
    'Max Value' => 'Valoare Maximă',
    'Pattern' => 'Pattern',
    'Options' => 'Opțiuni',
    'Multiple' => 'Multiplu',
    'Searchable' => 'Căutabil',
    
    // Settings
    'General Settings' => 'Setări Generale',
    'Site Settings' => 'Setări Site',
    'Site Title' => 'Titlu Site',
    'Site Description' => 'Descriere Site',
    'Site Logo' => 'Logo Site',
    'Favicon' => 'Favicon',
    'Timezone' => 'Fus Orar',
    'Date Format' => 'Format Dată',
    'Time Format' => 'Format Oră',
    'Language' => 'Limbă',
    'Currency' => 'Monedă',
    
    // SEO
    'SEO' => 'SEO',
    'SEO Settings' => 'Setări SEO',
    'Canonical URL' => 'URL Canonic',
    'Robots Meta Tag' => 'Tag Meta Robots',
    'Index' => 'Index',
    'NoIndex' => 'NoIndex',
    'Follow' => 'Follow',
    'NoFollow' => 'NoFollow',
    'Sitemap' => 'Hartă Site',
    'Schema Markup' => 'Schema Markup',
    
    // Actions
    'Save' => 'Salvează',
    'Save & Close' => 'Salvează & Închide',
    'Save Changes' => 'Salvează Modificările',
    'Cancel' => 'Anulează',
    'Submit' => 'Trimite',
    'Update' => 'Actualizează',
    'Create' => 'Creează',
    'Edit' => 'Editează',
    'Delete' => 'Șterge',
    'Remove' => 'Elimină',
    'Add' => 'Adaugă',
    'Add New' => 'Adaugă Nou',
    'Duplicate' => 'Duplică',
    'Clone' => 'Clonează',
    'Restore' => 'Restaurează',
    'Archive' => 'Arhivează',
    'Export' => 'Exportă',
    'Import' => 'Importă',
    'Publish' => 'Publică',
    'Unpublish' => 'Anulează Publicarea',
    'Preview' => 'Previzualizare',
    'View' => 'Vezi',
    'Search' => 'Caută',
    'Filter' => 'Filtrează',
    'Sort' => 'Sortează',
    'Refresh' => 'Reîmprospătează',
    'Reset' => 'Resetează',
    'Clear' => 'Șterge',
    'Apply' => 'Aplică',
    'Back' => 'Înapoi',
    
    // Messages
    'Success' => 'Succes',
    'Error' => 'Eroare',
    'Warning' => 'Avertisment',
    'Info' => 'Informație',
    'Saved Successfully' => 'Salvat cu Succes',
    'Created Successfully' => 'Creat cu Succes',
    'Updated Successfully' => 'Actualizat cu Succes',
    'Deleted Successfully' => 'Șters cu Succes',
    'Are you sure?' => 'Ești sigur?',
    'This action cannot be undone' => 'Această acțiune nu poate fi anulată',
    'Please confirm' => 'Te rugăm să confirmi',
    
    // Tables
    'No records found' => 'Nu s-au găsit înregistrări',
    'Showing' => 'Afișează',
    'to' => 'până la',
    'of' => 'din',
    'results' => 'rezultate',
    'per page' => 'pe pagină',
    'Page' => 'Pagina',
    'Go to page' => 'Mergi la pagina',
    'First' => 'Prima',
    'Last' => 'Ultima',
    'Previous' => 'Anterior',
    'Next' => 'Următorul',
    
    // Dates & Times
    'Today' => 'Astăzi',
    'Yesterday' => 'Ieri',
    'Tomorrow' => 'Mâine',
    'This Week' => 'Săptămâna Aceasta',
    'This Month' => 'Luna Aceasta',
    'This Year' => 'Anul Acesta',
    'Last Week' => 'Săptămâna Trecută',
    'Last Month' => 'Luna Trecută',
    'Last Year' => 'Anul Trecut',
    'Created At' => 'Creat La',
    'Updated At' => 'Actualizat La',
    'Deleted At' => 'Șters La',
    
    // Redirects
    'Redirect' => 'Redirecționare',
    'Redirects' => 'Redirecționări',
    'From URL' => 'De la URL',
    'To URL' => 'Către URL',
    'Redirect Type' => 'Tip Redirecționare',
    '301 for permanent, 302 for temporary redirects' => '301 pentru permanent, 302 pentru temporar',
    '/old-page' => '/pagina-veche',
    '/new-page' => '/pagina-noua',
    
    // Plugins
    'Plugin' => 'Plugin',
    'Plugins' => 'Plugin-uri',
    'Plugin Name' => 'Nume Plugin',
    'Version' => 'Versiune',
    'Installed' => 'Instalat',
    'Not Installed' => 'Neinstalat',
    'Install' => 'Instalează',
    'Uninstall' => 'Dezinstalează',
    
    // Freelancers
    '$/Oră' => '$/Oră',
    'Hourly Rate' => 'Tarif Orar',
    'Skills' => 'Abilități',
    'Experience' => 'Experiență',
    'Portfolio' => 'Portofoliu',
    'Rating' => 'Rating',
    
    // Contacts & Communications
    'Contact' => 'Contact',
    'Contacts' => 'Contacte',
    'Message' => 'Mesaj',
    'Messages' => 'Mesaje',
    'Subject' => 'Subiect',
    'Body' => 'Conținut',
    'Sender' => 'Expeditor',
    'Recipient' => 'Destinatar',
    'Date Sent' => 'Data Trimiterii',
    'Read' => 'Citit',
    'Unread' => 'Necitit',
    'Reply' => 'Răspunde',
    
    // Dashboard
    'Dashboard' => 'Panou Control',
    'Statistics' => 'Statistici',
    'Overview' => 'Prezentare Generală',
    'Recent Activity' => 'Activitate Recentă',
    'Quick Actions' => 'Acțiuni Rapide',
];

// Merge with existing translations
$existingRo = include(__DIR__ . '/lang/ro/messages.php');
$merged = array_merge($existingRo, $adminTranslations);

// Sort alphabetically
ksort($merged);

// Generate PHP file
$output = "<?php\n\n";
$output .= "/**\n";
$output .= " * RO Translation File for Carpathian CMS\n";
$output .= " * Updated: " . date('Y-m-d H:i:s') . "\n";
$output .= " * Total keys: " . count($merged) . "\n";
$output .= " */\n\n";
$output .= "return " . var_export($merged, true) . ";\n";

file_put_contents(__DIR__ . '/lang/ro/messages.php', $output);

echo "✅ Romanian admin translations added!\n";
echo "📊 Total translations: " . count($merged) . "\n";
echo "🆕 New admin translations added: " . count($adminTranslations) . "\n";

