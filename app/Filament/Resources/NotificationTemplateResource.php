<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Communications;

use App\Filament\Resources\NotificationTemplateResource\Pages;
use App\Models\NotificationTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-bell-alert';
    protected static ?string $cluster = Communications::class;
    protected static ?int $navigationSort = 4;

    public static function getNavigationLabel(): string
    {
        return __('Notification Templates');
    }

    public static function getModelLabel(): string
    {
        return __('Notification Template');
    }
    
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('Template Information'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('slug')
                        ->label(__('Slug'))
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Select::make('type')
                        ->label(__('Type'))
                        ->options([
                            'email' => __('Email'),
                            'sms' => __('SMS'),
                            'push' => __('Push Notification'),
                        ])
                        ->required()
                        ->default('email'),
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])->columns(2),
            
            Forms\Components\Section::make(__('Content'))
                ->schema([
                    Forms\Components\TextInput::make('subject')
                        ->label(__('Subject'))
                        ->maxLength(255)
                        ->helperText(__('Use {{variable}} for placeholders')),
                    Forms\Components\Textarea::make('body')
                        ->label(__('Body'))
                        ->required()
                        ->rows(10)
                        ->helperText(__('HTML content for email templates')),
                ]),
            
            Forms\Components\Section::make(__('Variables'))
                ->schema([
                    Forms\Components\TagsInput::make('variables')
                        ->label(__('Available Variables'))
                        ->placeholder(__('Add variable')),
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
                Tables\Columns\TextColumn::make('slug')
                    ->label(__('Slug'))
                    ->badge()
                    ->color('gray')
                    ->copyable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label(__('Type'))
                    ->colors([
                        'primary' => 'email',
                        'success' => 'sms',
                        'warning' => 'push',
                    ]),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'email' => __('Email'),
                        'sms' => __('SMS'),
                        'push' => __('Push'),
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNotificationTemplates::route('/'),
            'create' => Pages\CreateNotificationTemplate::route('/create'),
            'edit' => Pages\EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}
