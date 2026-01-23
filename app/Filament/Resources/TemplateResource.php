<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\CMS;

use App\Filament\Resources\TemplateResource\Pages;
use App\Models\Template;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class TemplateResource extends Resource


{
    public static function getNavigationGroup(): ?string
    {
        return __('Design');
    }

    public static function getNavigationLabel(): string
    {
        return __('Templates');
    }

    protected static ?string $model = Template::class;
    protected static ?string $cluster = CMS::class;
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
        protected static ?int $navigationSort = 1;
        protected static ?string $pluralModelLabel = 'Templates';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informații Template')
                ->schema([
                    Forms\Components\TextInput::make('name')
                                           ->label(__('Name'))
                        ->label('Nume')
                        ->required()
                        ->maxLength(255),
                    
                    Forms\Components\TextInput::make('slug')
                                           ->label(__('Slug'))
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    
                    Forms\Components\Textarea::make('description')
                                           ->label(__('Description'))
                        ->label('Descriere')
                        ->rows(3)
                        ->maxLength(1000),
                    
                    Forms\Components\TextInput::make('version')
                        ->label('Versiune')
                        ->default('1.0.0')
                        ->maxLength(20),
                    
                    Forms\Components\TextInput::make('author')
                        ->label('Autor')
                        ->default('Carpathian CMS')
                        ->maxLength(255),
                ])
                ->columns(2),
            
            Forms\Components\Section::make('Configurare')
                ->schema([
                    Forms\Components\KeyValue::make('config')
                        ->label('Configurare JSON')
                        ->helperText('Setări specifice template-ului'),
                    
                    Forms\Components\KeyValue::make('layouts')
                        ->label('Layouts Disponibile')
                        ->helperText('Tipuri de pagini suportate'),
                    
                    Forms\Components\Toggle::make('is_active')
                                           ->label(__('Active'))
                        ->label('Activ')
                        ->helperText('Template-ul este utilizat pe site')
                        ->default(false),
                    
                    Forms\Components\Toggle::make('is_default')
                        ->label('Implicit')
                        ->helperText('Template-ul implicit pentru conținut nou')
                        ->default(false),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                       ->label(__('Name'))
                    ->label('Nume Template')
                    ->searchable()
                    ->weight('bold')
                    ->icon(fn ($record) => $record->is_active ? 'heroicon-o-check-circle' : null)
                    ->iconColor('success')
                    ->description(fn ($record) => $record->description),
                
                Tables\Columns\TextColumn::make('slug')
                                       ->label(__('Slug'))
                    ->label('Slug')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('color_scheme')
                    ->label('Scheme Culori')
                    ->formatStateUsing(function ($record) {
                        $colors = is_string($record->color_scheme) 
                            ? json_decode($record->color_scheme, true) 
                            : $record->color_scheme;
                        
                        if (!$colors || !isset($colors['primary'])) return '-';
                        
                        return view('filament.tables.columns.color-preview', ['colors' => $colors]);
                    })
                    ->html(),
                
                Tables\Columns\TextColumn::make('version')
                    ->label('Versiune')
                    ->badge()
                    ->color('gray'),
                
                Tables\Columns\IconColumn::make('is_active')
                                       ->label(__('Active'))
                    ->label('Activ')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('gray'),
                
                Tables\Columns\IconColumn::make('is_default')
                    ->label('Implicit')
                    ->boolean()
                    ->trueIcon('heroicon-o-star')
                    ->falseIcon('heroicon-o-star')
                    ->trueColor('warning')
                    ->falseColor('gray'),
                
                Tables\Columns\TextColumn::make('created_at')
                                       ->label(__('Created At'))
                    ->label('Instalat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Activ'),
                Tables\Filters\TernaryFilter::make('is_default')
                    ->label('Implicit'),
            ])
            ->actions([
                Tables\Actions\Action::make('preview')
                    ->label('👁️ Preview')
                    ->icon('heroicon-o-eye')
                    ->color('info')
                    ->modalHeading(fn (Template $record) => "Preview: {$record->name}")
                    ->modalDescription(fn (Template $record) => $record->description)
                    ->modalWidth('5xl')
                    ->modalContent(fn (Template $record) => view('filament.modals.template-preview', [
                        'template' => $record,
                        'colors' => is_string($record->color_scheme) ? json_decode($record->color_scheme, true) : $record->color_scheme,
                        'typography' => is_string($record->typography) ? json_decode($record->typography, true) : $record->typography,
                        'config' => is_string($record->config) ? json_decode($record->config, true) : $record->config,
                    ]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Close'),

                Tables\Actions\Action::make('activate')
                    ->label('Activează')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Template $record) => !$record->is_active)
                    ->requiresConfirmation()
                    ->modalHeading('Activează Template')
                    ->modalDescription(fn (Template $record) => "Activează template-ul {$record->name}? Acesta va fi folosit pe site.")
                    ->action(function (Template $record) {
                        // Deactivate all other templates
                        Template::where('is_active', true)->update(['is_active' => false]);
                        $record->update(['is_active' => true]);
                        \Illuminate\Support\Facades\Cache::forget('active_template');
                        \Illuminate\Support\Facades\Cache::forget('default_template');
                        
                        Notification::make()
                            ->success()
                            ->title('Template activat!')
                            ->body("{$record->name} este acum activ pe site.")
                            ->send();
                    }),
                
                Tables\Actions\Action::make('deactivate')
                    ->label('Dezactivează')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->visible(fn (Template $record) => $record->is_active && !$record->is_default)
                    ->requiresConfirmation()
                    ->action(function (Template $record) {
                        $record->update(['is_active' => false]);
                        \Illuminate\Support\Facades\Cache::forget('active_template');
                        
                        Notification::make()
                            ->success()
                            ->title('Template dezactivat!')
                            ->send();
                    }),
                
                Tables\Actions\Action::make('set_default')
                    ->label('Setează Implicit')
                    ->icon('heroicon-o-star')
                    ->color('warning')
                    ->visible(fn (Template $record) => !$record->is_default)
                    ->requiresConfirmation()
                    ->modalHeading('Setează ca Implicit')
                    ->modalDescription(fn (Template $record) => "Setează {$record->name} ca template implicit pentru conținut nou?")
                    ->action(function (Template $record) {
                        Template::where('is_default', true)->update(['is_default' => false]);
                        $record->update(['is_default' => true, 'is_active' => true]);
                        \Illuminate\Support\Facades\Cache::forget('active_template');
                        \Illuminate\Support\Facades\Cache::forget('default_template');
                        
                        Notification::make()
                            ->success()
                            ->title('Template implicit setat!')
                            ->body("{$record->name} este acum template-ul implicit.")
                            ->send();
                    }),
                
                Tables\Actions\EditAction::make(),
                
                Tables\Actions\DeleteAction::make()
                    ->label('Dezinstalează')
                    ->requiresConfirmation()
                    ->modalHeading('Dezinstalează Template')
                    ->modalDescription(fn (Template $record) => "Ștergi template-ul {$record->name}? Fișierele vor rămâne pe server.")
                    ->before(function (Template $record) {
                        if ($record->is_default) {
                            Notification::make()
                                ->danger()
                                ->title('Eroare!')
                                ->body('Nu poți șterge template-ul implicit!')
                                ->send();
                            return false;
                        }
                    }),
            ])
            ->headerActions([
                Tables\Actions\Action::make('upload_template')
                    ->label('📤 Încarcă Template')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->color('primary')
                    ->modalHeading('�� Încarcă Template ZIP')
                    ->modalDescription('Încarcă un pachet ZIP de template (max 50MB)')
                    ->form([
                        Forms\Components\FileUpload::make('template_zip')
                            ->label('Fișier ZIP Template')
                            ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                            ->required()
                            ->maxSize(51200)
                            ->helperText('Format: template.zip cu folder template-slug/ și template.json în interior'),
                    ])
                    ->action(function (array $data) {
                        try {
                            $zipPath = storage_path('app/public/' . $data['template_zip']);
                            $extractPath = storage_path('app/public/templates');
                            
                            // Ensure templates directory exists with proper permissions
                            if (!file_exists($extractPath)) {
                                mkdir($extractPath, 0775, true);
                            }
                            
                            $zip = new \ZipArchive;
                            if ($zip->open($zipPath) === TRUE) {
                                // Extract to temporary directory first
                                $tempExtractPath = sys_get_temp_dir() . '/template_' . uniqid();
                                mkdir($tempExtractPath, 0755, true);
                                
                                $zip->extractTo($tempExtractPath);
                                $zip->close();
                                
                                // Find template.json in extracted folders
                                $templateDirs = array_filter(glob($tempExtractPath . '/*'), 'is_dir');
                                
                                $templateInstalled = false;
                                foreach ($templateDirs as $dir) {
                                    $manifestPath = $dir . '/template.json';
                                    if (file_exists($manifestPath)) {
                                        $manifest = json_decode(file_get_contents($manifestPath), true);
                                        
                                        // Generate slug from directory name or template name
                                        $slug = $manifest['slug'] ?? basename($dir);
                                        $name = $manifest['name'] ?? basename($dir);
                                        
                                        if (!Template::where('slug', $slug)->exists()) {
                                            // Final destination path
                                            $finalPath = $extractPath . '/' . $slug;
                                            
                                            // Remove existing directory if it exists (recursively)
                                            if (file_exists($finalPath)) {
                                                self::removeDirectory($finalPath);
                                            }
                                            
                                            // Copy from temp to final location (not rename, to avoid cross-device issues)
                                            self::copyDirectory($dir, $finalPath);
                                            
                                            // Set proper permissions
                                            chmod($finalPath, 0775);
                                            
                                            // Set ownership recursively using shell
                                            $output = null;
                                            $returnVar = null;
                                            exec('sudo chown -R www-data:www-data ' . escapeshellarg($finalPath) . ' 2>&1', $output, $returnVar);
                                            
                                            Template::create([
                                                'name' => $name,
                                                'slug' => $slug,
                                                'description' => $manifest['description'] ?? '',
                                                'version' => $manifest['version'] ?? '1.0.0',
                                                'author' => $manifest['author'] ?? 'Unknown',
                                                'author_url' => $manifest['author_url'] ?? '',
                                                'color_scheme' => $manifest['colors'] ?? ($manifest['color_scheme'] ?? []),
                                                'typography' => $manifest['typography'] ?? [],
                                                'config' => $manifest['config'] ?? [],
                                                'layouts' => $manifest['layouts'] ?? [],
                                                'is_active' => false,
                                                'is_default' => false,
                                            ]);
                                            
                                            $templateInstalled = true;
                                            
                                            Notification::make()
                                                ->success()
                                                ->title('🎉 Template instalat!')
                                                ->body("{$name} a fost instalat. Activează-l pentru a-l folosi.")
                                                ->duration(5000)
                                                ->send();
                                        } else {
                                            Notification::make()
                                                ->warning()
                                                ->title('Template deja instalat')
                                                ->body('Un template cu acest slug există deja.')
                                                ->send();
                                        }
                                        break;
                                    }
                                }
                                
                                // Clean up temp directory
                                self::removeDirectory($tempExtractPath);
                                
                                if (!$templateInstalled) {
                                    Notification::make()
                                        ->warning()
                                        ->title('Fișier template.json lipsește')
                                        ->body('Arhiva ZIP nu conține un fișier template.json valid.')
                                        ->send();
                                }
                                
                                @unlink($zipPath);
                            } else {
                                Notification::make()
                                    ->danger()
                                    ->title('Eroare la deschidere ZIP')
                                    ->body('Nu s-a putut deschide arhiva ZIP.')
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->danger()
                                ->title('Eroare')
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Dezinstalează Selectate')
                        ->before(function ($records) {
                            $defaultTemplate = $records->firstWhere('is_default', true);
                            if ($defaultTemplate) {
                                Notification::make()
                                    ->danger()
                                    ->title('Eroare!')
                                    ->body('Nu poți șterge template-ul implicit!')
                                    ->send();
                                return false;
                            }
                        }),
                ]),
            ])
            ->emptyStateHeading('Niciun template instalat')
            ->emptyStateDescription('Încarcă pachete ZIP de template pentru a începe')
            ->emptyStateIcon('heroicon-o-paint-brush');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTemplates::route('/'),
            'edit' => Pages\EditTemplate::route('/{record}/edit'),
        ];
    }

    /**
     * Recursively remove a directory
     */
    protected static function removeDirectory(string $dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }
        
        // Try using sudo rm -rf for permission issues
        $output = null;
        $returnVar = null;
        exec('sudo rm -rf ' . escapeshellarg($dir) . ' 2>&1', $output, $returnVar);
        
        if ($returnVar === 0 && !file_exists($dir)) {
            return true;
        }
        
        // Fallback to PHP native if sudo didn't work
        if (!is_dir($dir)) {
            return @unlink($dir);
        }
        
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            $path = $dir . DIRECTORY_SEPARATOR . $item;
            
            if (is_dir($path)) {
                self::removeDirectory($path);
            } else {
                @unlink($path);
            }
        }
        
        return @rmdir($dir);
    }

    /**
     * Recursively copy a directory
     */
    protected static function copyDirectory(string $source, string $dest): bool
    {
        if (!is_dir($source)) {
            return copy($source, $dest);
        }
        
        if (!file_exists($dest)) {
            mkdir($dest, 0775, true);
        }
        
        foreach (scandir($source) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            
            $srcPath = $source . DIRECTORY_SEPARATOR . $item;
            $destPath = $dest . DIRECTORY_SEPARATOR . $item;
            
            if (is_dir($srcPath)) {
                self::copyDirectory($srcPath, $destPath);
            } else {
                copy($srcPath, $destPath);
            }
        }
        
        return true;
    }
}
