<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use App\Services\GroqAiService;
use App\Models\Plugin;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class AiPluginGenerator extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-code-bracket';
    protected static string $view = 'filament.pages.ai-plugin-generator';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('AI');
    }

    public static function getNavigationLabel(): string
    {
        return __('AI Plugin Generator');
    }

    public function getTitle(): string
    {
        return __('AI Plugin Generator');
    }

    public ?array $data = [];
    public ?string $generatedCode = null;
    public ?string $generatedReadme = null;
    public bool $isGenerating = false;

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Plugin Name'))
                    ->required()
                    ->placeholder(__('e.g., Contact Form Manager'))
                    ->helperText(__('Enter a descriptive name for your plugin')),

                Textarea::make('description')
                    ->label(__('Plugin Description'))
                    ->required()
                    ->rows(4)
                    ->placeholder(__('Describe what your plugin should do...'))
                    ->helperText(__('Be as detailed as possible about the features and functionality')),

                Select::make('plugin_type')
                    ->label(__('Plugin Type'))
                    ->required()
                    ->options([
                        'widget' => __('Widget - Display custom content'),
                        'shortcode' => __('Shortcode - Embed dynamic content'),
                        'module' => __('Module - Extend functionality'),
                        'integration' => __('Integration - Connect with external services'),
                        'admin_tool' => __('Admin Tool - Backend utilities'),
                    ])
                    ->default('module'),

                Textarea::make('requirements')
                    ->label(__('Specific Requirements (Optional)'))
                    ->rows(3)
                    ->placeholder(__('List any specific features, integrations, or technical requirements...'))
                    ->helperText(__('e.g., "Must use Bootstrap 5", "Should integrate with SendGrid API"')),
            ])
            ->statePath('data');
    }

    public function generate(): void
    {
        $this->validate();

        $this->isGenerating = true;
        $this->generatedCode = null;
        $this->generatedReadme = null;

        try {
            $groqService = new GroqAiService();
            
            $result = $groqService->generatePlugin($this->data['description'], [
                'name' => $this->data['name'],
                'type' => $this->data['plugin_type'],
                'requirements' => $this->data['requirements'] ?? '',
            ]);

            if ($result['success']) {
                $data = $result['data'];
                $this->generatedCode = $data['code'] ?? '';
                $this->generatedReadme = $data['readme'] ?? $data['documentation'] ?? '';

                Notification::make()
                    ->title(__('Plugin generated successfully!'))
                    ->success()
                    ->body(__('Review the code and save it as a plugin.'))
                    ->send();
            } else {
                throw new \Exception($result['error'] ?? 'Failed to generate plugin');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Generation failed'))
                ->danger()
                ->body($e->getMessage())
                ->send();
        } finally {
            $this->isGenerating = false;
        }
    }

    public function saveAsPlugin(): void
    {
        if (!$this->generatedCode) {
            Notification::make()
                ->title(__('No code to save'))
                ->warning()
                ->body(__('Please generate plugin code first'))
                ->send();
            return;
        }

        try {
            $slug = Str::slug($this->data['name']);
            $pluginPath = "plugins/{$slug}";

            // Create plugin directory structure
            Storage::disk('local')->makeDirectory($pluginPath);
            
            // Save main plugin file
            Storage::disk('local')->put(
                "{$pluginPath}/{$slug}.php",
                $this->generatedCode
            );

            // Save README if available
            if ($this->generatedReadme) {
                Storage::disk('local')->put(
                    "{$pluginPath}/README.md",
                    $this->generatedReadme
                );
            }

            // Create database record
            $plugin = Plugin::create([
                'name' => $this->data['name'],
                'slug' => $slug,
                'description' => $this->data['description'],
                'version' => '1.0.0',
                'author' => auth()->user()->name ?? 'AI Generator',
                'file_path' => $pluginPath,
                'is_active' => false,
                'settings' => [
                    'type' => $this->data['plugin_type'],
                    'generated_by_ai' => true,
                    'requirements' => $this->data['requirements'] ?? null,
                ],
            ]);

            Notification::make()
                ->title(__('Plugin saved successfully!'))
                ->success()
                ->body(__("Plugin ':name' has been created and saved.", ['name' => $this->data['name']]))
                ->send();

            // Reset form
            $this->form->fill();
            $this->generatedCode = null;
            $this->generatedReadme = null;

        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Save failed'))
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
