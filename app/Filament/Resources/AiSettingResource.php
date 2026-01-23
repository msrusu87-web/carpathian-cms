<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\CMS;

use App\Filament\Resources\AiSettingResource\Pages;
use App\Models\AiSetting;
use App\Services\AIService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class AiSettingResource extends Resource
{

    public static function getNavigationLabel(): string
    {
        return __('AI Settings');
    }
    protected static ?string $model = AiSetting::class;
    protected static ?string $cluster = CMS::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    
    protected static ?int $navigationSort = 4;

    

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Provider Details')
                    ->schema([
                        Forms\Components\Select::make('provider')
                                                ->label(__('Provider'))
                            ->options(AiSetting::getProviders())
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Choose the AI provider'),

                        Forms\Components\TextInput::make('name')
                                                ->label(__('Name'))
                            ->required()
                            ->maxLength(255)
                            ->helperText('Display name for this provider'),

                        Forms\Components\Textarea::make('api_key')
                                               ->label(__('API Key'))
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->rows(2)
                            ->columnSpanFull()
                            ->helperText('Enter your API key (keep it secure)'),

                        Forms\Components\TextInput::make('model')
                            ->helperText('Model name (e.g., gpt-4, llama-3.3-70b-versatile, gemini-pro)'),

                        Forms\Components\TextInput::make('order')
                                                ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->helperText('Display order'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                                               ->label(__('Active'))
                            ->label('Active')
                            ->default(false)
                            ->helperText('Enable this AI provider'),

                        Forms\Components\Toggle::make('is_default')
                            ->label('Set as Default')
                            ->default(false)
                            ->helperText('Use this provider by default'),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Advanced Configuration')
                    ->schema([
                        Forms\Components\KeyValue::make('config')
                            ->label('Additional Settings')
                            ->helperText('JSON configuration (e.g., temperature, max_tokens)')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                        ->label(__('Name'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('provider')
                                        ->label(__('Provider'))
                    ->formatStateUsing(fn (string $state): string => AiSetting::getProviders()[$state] ?? $state)
                    ->colors([
                        'success' => 'groq',
                        'primary' => 'chatgpt',
                        'warning' => 'gemini',
                        'info' => 'claude',
                    ]),

                Tables\Columns\IconColumn::make('api_key')
                                       ->label(__('API Key'))
                    ->label('API Key')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->getStateUsing(fn ($record) => !empty($record->api_key)),

                Tables\Columns\TextColumn::make('model')
                    ->default('—'),

                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),

                Tables\Columns\IconColumn::make('is_default')
                    ->label('Default')
                    ->boolean(),

                Tables\Columns\TextColumn::make('order')
                                        ->label(__('Order'))
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Only'),
            ])
            ->actions([
                Tables\Actions\Action::make('test')
                    ->label('Test Connection')
                    ->icon('heroicon-o-signal')
                    ->color('info')
                    ->action(function (AiSetting $record) {
                        try {
                            $service = new AIService($record->provider);
                            if ($service->testConnection()) {
                                Notification::make()
                                    ->title('Connection Successful!')
                                    ->body("Successfully connected to {$record->name}")
                                    ->success()
                                    ->send();
                            } else {
                                Notification::make()
                                    ->title('Connection Failed')
                                    ->body("Could not connect to {$record->name}")
                                    ->danger()
                                    ->send();
                            }
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.ai');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiSettings::route('/'),
            'create' => Pages\CreateAiSetting::route('/create'),
            'edit' => Pages\EditAiSetting::route('/{record}/edit'),
        ];
    }
}
