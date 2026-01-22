<?php

namespace App\Filament\Resources\LanguageResource\Pages;

use App\Filament\Resources\LanguageResource;
use App\Models\Language;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\File;

class ManageTranslations extends Page
{
    protected static string $resource = LanguageResource::class;

    protected static string $view = 'filament.resources.language-resource.pages.manage-translations';

    protected static ?string $title = 'Editează Traduceri';

    public ?array $data = [];
    public $selectedLanguage = null;
    public $selectedFile = null;
    public $translations = [];
    public $availableFiles = [];
    public $newKey = '';
    public $newValue = '';
    public $searchTerm = '';

    public function mount(): void
    {
        $languages = Language::where('is_active', true)->pluck('code')->toArray();
        if (!empty($languages)) {
            $this->selectedLanguage = $languages[0];
            $this->loadAvailableFiles();
            if (!empty($this->availableFiles)) {
                $this->selectedFile = $this->availableFiles[0]['value'];
                $this->loadTranslations();
            }
        }
    }

    public function loadAvailableFiles(): void
    {
        if (!$this->selectedLanguage) {
            $this->availableFiles = [];
            return;
        }

        $langPath = lang_path($this->selectedLanguage);
        $files = [];

        // Add JSON file
        $jsonFile = lang_path("{$this->selectedLanguage}.json");
        if (File::exists($jsonFile)) {
            $files[] = [
                'label' => "{$this->selectedLanguage}.json (Filament Translations)",
                'value' => 'json',
            ];
        }

        // Add PHP files from language directory
        if (File::isDirectory($langPath)) {
            foreach (File::files($langPath) as $file) {
                if ($file->getExtension() === 'php') {
                    $filename = $file->getFilenameWithoutExtension();
                    $files[] = [
                        'label' => "{$filename}.php",
                        'value' => $filename,
                    ];
                }
            }
        }

        $this->availableFiles = $files;
    }

    public function loadTranslations(): void
    {
        if (!$this->selectedLanguage || !$this->selectedFile) {
            $this->translations = [];
            return;
        }

        if ($this->selectedFile === 'json') {
            $file = lang_path("{$this->selectedLanguage}.json");
            if (File::exists($file)) {
                $content = File::get($file);
                $this->translations = json_decode($content, true) ?? [];
            }
        } else {
            $file = lang_path("{$this->selectedLanguage}/{$this->selectedFile}.php");
            if (File::exists($file)) {
                $this->translations = include $file;
            }
        }

        ksort($this->translations);
    }

    public function updatedSelectedLanguage(): void
    {
        $this->loadAvailableFiles();
        if (!empty($this->availableFiles)) {
            $this->selectedFile = $this->availableFiles[0]['value'];
            $this->loadTranslations();
        }
    }

    public function updatedSelectedFile(): void
    {
        $this->loadTranslations();
    }

    public function updateTranslation($key, $value): void
    {
        $this->translations[$key] = $value;
    }

    public function deleteTranslation($key): void
    {
        if (isset($this->translations[$key])) {
            unset($this->translations[$key]);
            $this->saveTranslations();
            
            Notification::make()
                ->title('Traducere ștearsă')
                ->success()
                ->send();
        }
    }

    public function addTranslation(): void
    {
        if (empty($this->newKey) || empty($this->newValue)) {
            Notification::make()
                ->title('Eroare')
                ->body('Cheia și valoarea sunt obligatorii')
                ->danger()
                ->send();
            return;
        }

        if (isset($this->translations[$this->newKey])) {
            Notification::make()
                ->title('Eroare')
                ->body('Cheia există deja')
                ->warning()
                ->send();
            return;
        }

        $this->translations[$this->newKey] = $this->newValue;
        $this->newKey = '';
        $this->newValue = '';
        
        $this->saveTranslations();
        
        Notification::make()
            ->title('Traducere adăugată')
            ->success()
            ->send();
    }

    public function saveTranslations(): void
    {
        if (!$this->selectedLanguage || !$this->selectedFile) {
            Notification::make()
                ->title('Eroare')
                ->body('Selectează limba și fișierul')
                ->danger()
                ->send();
            return;
        }

        ksort($this->translations);

        try {
            if ($this->selectedFile === 'json') {
                $file = lang_path("{$this->selectedLanguage}.json");
                File::put($file, json_encode($this->translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            } else {
                $file = lang_path("{$this->selectedLanguage}/{$this->selectedFile}.php");
                $export = "<?php\n\nreturn " . var_export($this->translations, true) . ";\n";
                File::put($file, $export);
            }

            // Clear cache
            \Artisan::call('cache:clear');
            \Artisan::call('config:clear');

            Notification::make()
                ->title('Traduceri salvate!')
                ->body('Fișierul a fost actualizat cu succes')
                ->success()
                ->send();

            $this->loadTranslations();
        } catch (\Exception $e) {
            Notification::make()
                ->title('Eroare la salvare')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    public function createNewLanguage(): void
    {
        $this->redirect(LanguageResource::getUrl('create'));
    }

    public function getFilteredTranslations(): array
    {
        if (empty($this->searchTerm)) {
            return $this->translations;
        }

        return array_filter($this->translations, function ($value, $key) {
            return stripos($key, $this->searchTerm) !== false || 
                   stripos($value, $this->searchTerm) !== false;
        }, ARRAY_FILTER_USE_BOTH);
    }
}
