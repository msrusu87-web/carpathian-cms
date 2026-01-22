<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Communications;

use App\Filament\Resources\ContactMessageResource\Pages;
use App\Models\ContactMessage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContactMessageResource extends Resource
{
    protected static ?string $model = ContactMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox';
    protected static ?string $cluster = Communications::class;
    protected static ?int $navigationSort = 2;

    public static function getNavigationLabel(): string
    {
        return __('Contact Messages');
    }

    public static function getModelLabel(): string
    {
        return __('Contact Message');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'new')->count() ?: null;
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make(__('Message Details'))
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->disabled(),
                    Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->disabled(),
                    Forms\Components\TextInput::make('phone')
                        ->label(__('Phone'))
                        ->disabled(),
                    Forms\Components\TextInput::make('subject')
                        ->label(__('Subject'))
                        ->disabled(),
                    Forms\Components\Textarea::make('message')
                        ->label(__('Message'))
                        ->disabled()
                        ->columnSpanFull()
                        ->rows(5),
                ])->columns(2),
            
            Forms\Components\Section::make(__('Status'))
                ->schema([
                    Forms\Components\Select::make('status')
                        ->label(__('Status'))
                        ->options([
                            'new' => __('New'),
                            'read' => __('Read'),
                            'replied' => __('Replied'),
                            'archived' => __('Archived'),
                        ])
                        ->required(),
                    Forms\Components\DateTimePicker::make('replied_at')
                        ->label(__('Replied At')),
                ])->columns(2),
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
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->limit(30),
                Tables\Columns\BadgeColumn::make('status')
                    ->label(__('Status'))
                    ->colors([
                        'danger' => 'new',
                        'warning' => 'read',
                        'success' => 'replied',
                        'gray' => 'archived',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label(__('Received'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'new' => __('New'),
                        'read' => __('Read'),
                        'replied' => __('Replied'),
                        'archived' => __('Archived'),
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markRead')
                    ->label(__('Mark as Read'))
                    ->icon('heroicon-o-eye')
                    ->action(fn ($record) => $record->update(['status' => 'read']))
                    ->visible(fn ($record) => $record->status === 'new'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListContactMessages::route('/'),
            'create' => Pages\CreateContactMessage::route('/create'),
            'edit' => Pages\EditContactMessage::route('/{record}/edit'),
        ];
    }
}
