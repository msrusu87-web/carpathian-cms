<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PluginResource\Pages;
use App\Models\Plugin;
use App\Services\PluginManager;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PluginResource extends Resource
{
    protected static ?string $model = Plugin::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $navigationGroup = 'Setări';
    protected static ?int $navigationSort = 10;
    protected static ?string $navigationLabel = 'Plugin-uri';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informații Plugin')->schema([
                Forms\Components\TextInput::make('name')->required()->label('Nume'),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true)->label('Slug'),
                Forms\Components\Textarea::make('description')->rows(3)->label('Descriere'),
                Forms\Components\TextInput::make('version')->label('Versiune')->default('1.0.0'),
                Forms\Components\TextInput::make('author')->label('Autor')->default('Carphatian CMS'),
            ]),
            Forms\Components\Section::make('Configurare')->schema([
                Forms\Components\KeyValue::make('config')->label('Configurare JSON'),
                Forms\Components\Toggle::make('is_active')->label('Activ')->default(false)->disabled(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')
                ->searchable()
                ->label('Nume Plugin')
                ->weight('bold')
                ->icon(fn ($record) => $record->is_active ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                ->iconColor(fn ($record) => $record->is_active ? 'success' : 'gray'),
            Tables\Columns\TextColumn::make('slug')->badge()->color('info')->label('ID'),
            Tables\Columns\TextColumn::make('version')->badge()->label('Versiune'),
            Tables\Columns\TextColumn::make('author')->label('Autor'),
            Tables\Columns\IconColumn::make('is_active')->boolean()->label('Status'),
            Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Instalat')->since(),
        ])->actions([
            Tables\Actions\Action::make('activate')
                ->label('Activează')
                ->icon('heroicon-o-play')
                ->color('success')
                ->visible(fn (Plugin $record) => !$record->is_active)
                ->requiresConfirmation()
                ->modalHeading('Activare Plugin')
                ->modalDescription(fn (Plugin $record) => "Activați {$record->name}? Meniurile vor apărea în admin.")
                ->action(function (Plugin $record) {
                    $manager = app(PluginManager::class);
                    if ($manager->activate($record)) {
                        Notification::make()->success()->title('Plugin activat!')->body('Reîmprospătează pagina.')->send();
                    } else {
                        Notification::make()->danger()->title('Eroare la activare')->send();
                    }
                }),
            
            Tables\Actions\Action::make('deactivate')
                ->label('Dezactivează')
                ->icon('heroicon-o-pause')
                ->color('warning')
                ->visible(fn (Plugin $record) => $record->is_active)
                ->requiresConfirmation()
                ->modalHeading('Dezactivare Plugin')
                ->modalDescription(fn (Plugin $record) => "Dezactivați {$record->name}? Meniurile vor dispărea.")
                ->action(function (Plugin $record) {
                    $manager = app(PluginManager::class);
                    if ($manager->deactivate($record)) {
                        Notification::make()->success()->title('Plugin dezactivat!')->body('Reîmprospătează pagina.')->send();
                    } else {
                        Notification::make()->danger()->title('Eroare')->send();
                    }
                }),
            
            Tables\Actions\EditAction::make(),
            
            Tables\Actions\DeleteAction::make()
                ->label('Dezinstalează')
                ->requiresConfirmation()
                ->modalHeading('Dezinstalare Plugin')
                ->modalDescription(fn (Plugin $record) => "ATENȚIE! Șterge PERMANENT datele {$record->name}!")
                ->before(function (Plugin $record) {
                    app(PluginManager::class)->uninstall($record);
                }),
        ])->headerActions([
            Tables\Actions\Action::make('upload_plugin')
                ->label('📤 Încarcă Plugin')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('primary')
                ->modalHeading('📦 Încarcă Plugin ZIP')
                ->modalDescription('Selectează un fișier ZIP care conține plugin-ul (max 10MB). Mai târziu vei avea marketplace pentru plugin-uri gratuite sau cu plată.')
                ->form([
                    Forms\Components\FileUpload::make('plugin_zip')
                        ->label('Fișier ZIP Plugin')
                        ->acceptedFileTypes(['application/zip', 'application/x-zip-compressed'])
                        ->required()
                        ->maxSize(10240)
                        ->helperText('Format: plugin.json + src/ + database/migrations/'),
                ])
                ->action(function (array $data) {
                    $manager = app(PluginManager::class);
                    try {
                        $zipPath = storage_path('app/public/' . $data['plugin_zip']);
                        $extractPath = base_path('plugins');
                        
                        $zip = new \ZipArchive;
                        if ($zip->open($zipPath) === TRUE) {
                            $zip->extractTo($extractPath);
                            $zip->close();
                            
                            $pluginDirs = array_filter(glob($extractPath . '/*'), 'is_dir');
                            foreach ($pluginDirs as $dir) {
                                $manifestPath = $dir . '/plugin.json';
                                if (file_exists($manifestPath)) {
                                    $manifest = json_decode(file_get_contents($manifestPath), true);
                                    
                                    if (!Plugin::where('slug', $manifest['slug'])->exists()) {
                                        $manager->install($manifest['slug'], [
                                            'name' => $manifest['name'],
                                            'description' => $manifest['description'] ?? '',
                                            'version' => $manifest['version'] ?? '1.0.0',
                                            'author' => $manifest['author'] ?? 'Unknown',
                                            'config' => $manifest['config'] ?? [],
                                        ]);
                                        
                                        Notification::make()->success()
                                            ->title('🎉 Plugin instalat!')
                                            ->body("{$manifest['name']} a fost instalat. Activează-l pentru a-l folosi.")
                                            ->duration(5000)->send();
                                    } else {
                                        Notification::make()->warning()->title('Plugin deja instalat')->send();
                                    }
                                    break;
                                }
                            }
                            @unlink($zipPath);
                        } else {
                            Notification::make()->danger()->title('Eroare ZIP')->send();
                        }
                    } catch (\Exception $e) {
                        Notification::make()->danger()->title('Eroare')->body($e->getMessage())->send();
                    }
                }),
        ])->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make()->label('Dezinstalează'),
            ]),
        ])->emptyStateHeading('Niciun plugin instalat')
          ->emptyStateDescription('Încarcă plugin-uri ZIP sau achiziționează din marketplace (viitor)')
          ->emptyStateIcon('heroicon-o-puzzle-piece');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPlugins::route('/'),
            'create' => Pages\CreatePlugin::route('/create'),
            'edit' => Pages\EditPlugin::route('/{record}/edit'),
        ];
    }
}
