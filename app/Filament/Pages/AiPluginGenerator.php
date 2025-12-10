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
    protected static ?string $navigationLabel = 'AI Plugin Generator';
    protected static ?string $navigationGroup = 'AI';
    protected static ?int $navigationSort = 2;
    protected static ?string $title = 'AI Plugin Generator';

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
                    ->label('Plugin Name')
                    ->required()
                    ->placeholder('e.g., Contact Form Manager')
                    ->helperText('Enter a descriptive name for your plugin'),

                Textarea::make('description')
                    ->label('Plugin Description')
                    ->required()
                    ->rows(4)
                    ->placeholder('Describe what your plugin should do...')
                    ->helperText('Be as detailed as possible about the features and functionality'),

                Select::make('plugin_type')
                    ->label('Plugin Type')
                    ->required()
                    ->options([
                        'widget' => 'Widget - Display custom content',
                        'shortcode' => 'Shortcode - Embed dynamic content',
                        'module' => 'Module - Extend functionality',
                        'integration' => 'Integration - Connect with external services',
                        'admin_tool' => 'Admin Tool - Backend utilities',
                    ])
                    ->default('module'),

                Textarea::make('requirements')
                    ->label('Specific Requirements (Optional)')
                    ->rows(3)
                    ->placeholder('List any specific features, integrations, or technical requirements...')
                    ->helperText('e.g., "Must use Bootstrap 5", "Should integrate with SendGrid API"'),
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
                    ->title('Plugin generated successfully!')
                    ->success()
                    ->body('Review the code and save it as a plugin.')
                    ->send();
            } else {
                throw new \Exception($result['error'] ?? 'Failed to generate plugin');
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Generation failed')
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
                ->title('No code to save')
                ->warning()
                ->body('Please generate plugin code first')
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
                ->title('Plugin saved successfully!')
                ->success()
                ->body("Plugin '{$this->data['name']}' has been created and saved.")
                ->send();

            // Reset form
            $this->form->fill();
            $this->generatedCode = null;
            $this->generatedReadme = null;

        } catch (\Exception $e) {
            Notification::make()
                ->title('Save failed')
                ->danger()
                ->body($e->getMessage())
                ->send();
        }
    }
}
