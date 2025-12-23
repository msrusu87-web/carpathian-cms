<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Settings;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    public static function getNavigationLabel(): string
    {
        return __('Settings');
    }


    protected static ?string $navigationIcon = 'heroicon-o-cog-8-tooth';
    protected static ?string $cluster = Settings::class;

    
    protected static ?int $navigationSort = 1;
    protected static ?string $model = Setting::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('key')
                                        ->label(__('Key'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('value')
                                        ->label(__('Value'))
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('type')
                                        ->label(__('Type'))
                    ->required()
                    ->maxLength(255)
                    ->default('string'),
                Forms\Components\TextInput::make('group')
                                        ->label(__('Group'))
                    ->required()
                    ->maxLength(255)
                    ->default('general'),
                Forms\Components\Textarea::make('description')
                                        ->label(__('Description'))
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_public')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')
                                        ->label(__('Key'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                                        ->label(__('Type'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('group')
                                        ->label(__('Group'))
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_public')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                                        ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                        ->label(__('Updated At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.settings');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
