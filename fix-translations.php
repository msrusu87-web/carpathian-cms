<?php
/**
 * Fix Missing Translations Script
 * Adds all missing translation keys to all language files
 * Run: php fix-translations.php
 */

class TranslationFixer
{
    private $basePath;
    private $langPath;
    private $languages = ['en', 'ro', 'de', 'es', 'fr', 'it'];
    
    // Missing keys with English translations
    private $newTranslations = [
        // Product/Shop Page Keys
        'prices_include_vat' => 'Prices include VAT',
        'quantity' => 'Quantity',
        'pre_sale_button' => 'Pre-order',
        'fast_delivery' => 'Fast Delivery',
        'business_days' => 'business days',
        'warranty' => 'Warranty',
        'months' => 'months',
        'return_policy' => 'Return Policy',
        'days' => 'days',
        'support_247' => 'Support 24/7',
        
        // Checkout Keys
        'guest_checkout' => 'Guest Checkout',
        'billing_address' => 'Billing Address',
        'shipping_method' => 'Shipping Method',
        'payment_info' => 'Payment Information',
        'city' => 'City',
        'state' => 'State/Province',
        'postal_code' => 'Postal Code',
        'country' => 'Country',
        'company_name' => 'Company Name',
        'vat_number' => 'VAT Number',
        'order_notes' => 'Order Notes',
        'same_as_billing' => 'Same as billing address',
        'shipping_method_required' => 'Please select a shipping method',
        'payment_method_required' => 'Please select a payment method',
        
        // Portfolio Keys
        'our_work' => 'Our Work',
        'project_details' => 'Project Details',
        'view_project' => 'View Project',
        'next_project' => 'Next Project',
        'previous_project' => 'Previous Project',
        'project_category' => 'Project Category',
        
        // Blog/Content Keys
        'share_article' => 'Share Article',
        'print_article' => 'Print Article',
        'bookmark' => 'Bookmark',
        'read_time' => 'Read Time',
        'min_read' => 'min read',
        'written_by' => 'Written by',
        'in_category' => 'in',
        'tagged_with' => 'Tagged with',
        
        // General UI
        'loading_content' => 'Loading content...',
        'no_results' => 'No results found',
        'try_different_search' => 'Try a different search',
        'clear_filters' => 'Clear Filters',
        'apply_filters' => 'Apply Filters',
        'results_count' => 'results',
        'per_page' => 'per page',
        'go_to_page' => 'Go to page',
        'first_page' => 'First',
        'last_page' => 'Last',
        
        // Forms
        'required' => 'Required',
        'optional' => 'Optional',
        'placeholder_search' => 'Type to search...',
        'select_option' => 'Select an option',
        'no_options' => 'No options available',
        'add' => 'Add',
        'remove_item' => 'Remove',
        'clear_all' => 'Clear All',
        'reset_form' => 'Reset Form',
        'form_validation_error' => 'Please check the form for errors',
        
        // Status Messages
        'processing' => 'Processing...',
        'completed' => 'Completed',
        'pending' => 'Pending',
        'cancelled' => 'Cancelled',
        'refunded' => 'Refunded',
        'shipped' => 'Shipped',
        'delivered' => 'Delivered',
        
        // Actions
        'view_more' => 'View More',
        'show_less' => 'Show Less',
        'load_more' => 'Load More',
        'refresh' => 'Refresh',
        'reload' => 'Reload',
        'duplicate' => 'Duplicate',
        'archive' => 'Archive',
        'restore' => 'Restore',
        'permanently_delete' => 'Permanently Delete',
        
        // Misc
        'powered_by' => 'Powered by',
        'version' => 'Version',
        'last_updated' => 'Last Updated',
        'created_at' => 'Created',
        'modified_at' => 'Modified',
        'author_by' => 'By',
        'in' => 'in',
        'at' => 'at',
        'on' => 'on',
        'and' => 'and',
        'or' => 'or',
        'of' => 'of',
        'to' => 'to',
        'from' => 'from',
        'with' => 'with',
    ];

    // Romanian translations
    private $roTranslations = [
        'prices_include_vat' => 'Prețurile includ TVA',
        'quantity' => 'Cantitate',
        'pre_sale_button' => 'Precomandă',
        'fast_delivery' => 'Livrare Rapidă',
        'business_days' => 'zile lucrătoare',
        'warranty' => 'Garanție',
        'months' => 'luni',
        'return_policy' => 'Politică Retur',
        'days' => 'zile',
        'support_247' => 'Suport 24/7',
        
        'guest_checkout' => 'Finalizare fără cont',
        'billing_address' => 'Adresă Facturare',
        'shipping_method' => 'Metodă Livrare',
        'payment_info' => 'Informații Plată',
        'city' => 'Oraș',
        'state' => 'Județ',
        'postal_code' => 'Cod Poștal',
        'country' => 'Țară',
        'company_name' => 'Nume Companie',
        'vat_number' => 'Cod TVA',
        'order_notes' => 'Notițe Comandă',
        'same_as_billing' => 'Aceeași cu adresa de facturare',
        'shipping_method_required' => 'Vă rugăm selectați o metodă de livrare',
        'payment_method_required' => 'Vă rugăm selectați o metodă de plată',
        
        'our_work' => 'Munca Noastră',
        'project_details' => 'Detalii Proiect',
        'view_project' => 'Vezi Proiectul',
        'next_project' => 'Proiect Următor',
        'previous_project' => 'Proiect Anterior',
        'project_category' => 'Categorie Proiect',
        
        'share_article' => 'Distribuie Articolul',
        'print_article' => 'Tipărește Articolul',
        'bookmark' => 'Salvează',
        'read_time' => 'Timp Citire',
        'min_read' => 'min citire',
        'written_by' => 'Scris de',
        'in_category' => 'în',
        'tagged_with' => 'Etichetat cu',
        
        'loading_content' => 'Se încarcă conținutul...',
        'no_results' => 'Nu s-au găsit rezultate',
        'try_different_search' => 'Încearcă o căutare diferită',
        'clear_filters' => 'Șterge Filtrele',
        'apply_filters' => 'Aplică Filtrele',
        'results_count' => 'rezultate',
        'per_page' => 'pe pagină',
        'go_to_page' => 'Mergi la pagina',
        'first_page' => 'Prima',
        'last_page' => 'Ultima',
        
        'required' => 'Obligatoriu',
        'optional' => 'Opțional',
        'placeholder_search' => 'Tastează pentru a căuta...',
        'select_option' => 'Selectează o opțiune',
        'no_options' => 'Nu există opțiuni disponibile',
        'add' => 'Adaugă',
        'remove_item' => 'Elimină',
        'clear_all' => 'Șterge Tot',
        'reset_form' => 'Resetează Formularul',
        'form_validation_error' => 'Verifică formularul pentru erori',
        
        'processing' => 'Se procesează...',
        'completed' => 'Finalizat',
        'pending' => 'În așteptare',
        'cancelled' => 'Anulat',
        'refunded' => 'Rambursat',
        'shipped' => 'Expediat',
        'delivered' => 'Livrat',
        
        'view_more' => 'Vezi Mai Mult',
        'show_less' => 'Arată Mai Puțin',
        'load_more' => 'Încarcă Mai Mult',
        'refresh' => 'Reîmprospătează',
        'reload' => 'Reîncarcă',
        'duplicate' => 'Duplică',
        'archive' => 'Arhivează',
        'restore' => 'Restaurează',
        'permanently_delete' => 'Șterge Permanent',
        
        'powered_by' => 'Propulsat de',
        'version' => 'Versiune',
        'last_updated' => 'Ultima Actualizare',
        'created_at' => 'Creat',
        'modified_at' => 'Modificat',
        'author_by' => 'De',
        'in' => 'în',
        'at' => 'la',
        'on' => 'pe',
        'and' => 'și',
        'or' => 'sau',
        'of' => 'din',
        'to' => 'către',
        'from' => 'de la',
        'with' => 'cu',
    ];

    public function __construct()
    {
        $this->basePath = __DIR__;
        $this->langPath = $this->basePath . '/lang';
    }

    public function fix()
    {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "Carpathian CMS - Translation Fixer\n";
        echo str_repeat("=", 80) . "\n\n";

        foreach ($this->languages as $lang) {
            $this->updateLanguageFile($lang);
        }

        echo "\n" . str_repeat("=", 80) . "\n";
        echo "COMPLETED - All translation files updated!\n";
        echo str_repeat("=", 80) . "\n\n";
        
        echo "Next steps:\n";
        echo "1. Run: php artisan config:clear\n";
        echo "2. Run: php artisan cache:clear\n";
        echo "3. Run: php artisan view:clear\n";
        echo "4. Test the website in all languages\n\n";
    }

    private function updateLanguageFile($lang)
    {
        $messagesFile = $this->langPath . '/' . $lang . '/messages.php';
        
        if (!file_exists($messagesFile)) {
            echo "Creating new file: {$messagesFile}\n";
            $existingTranslations = [];
        } else {
            echo "Updating: {$messagesFile}\n";
            $existingTranslations = include $messagesFile;
        }

        // Determine which translations to use
        $newKeys = $this->newTranslations;
        if ($lang === 'ro') {
            $newKeys = $this->roTranslations;
        }

        // Merge translations
        $updated = array_merge($existingTranslations, $newKeys);
        
        // Sort alphabetically
        ksort($updated);

        // Write the file
        $content = "<?php\n\n/**\n * " . strtoupper($lang) . " Translation File for Carpathian CMS\n";
        $content .= " * Updated: " . date('Y-m-d H:i:s') . "\n */\n\n";
        $content .= "return " . var_export($updated, true) . ";\n";

        file_put_contents($messagesFile, $content);
        
        $addedCount = count($newKeys);
        echo "  + Added {$addedCount} new translations\n";
        echo "  Total keys: " . count($updated) . "\n\n";
    }
}

// Execute fixer
$fixer = new TranslationFixer();
$fixer->fix();
