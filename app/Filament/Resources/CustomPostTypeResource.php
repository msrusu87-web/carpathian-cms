<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomPostTypeResource\Pages;
use App\Models\CustomPostType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CustomPostTypeResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return __('Content');
    }

    public static function getNavigationLabel(): string
    {
        return __('Custom Post Types');
    }

    protected static ?string $model = CustomPostType::class;
    protected static ?string $navigationIcon = 'heroicon-o-cube';
    
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Textarea::make('description'),
                Forms\Components\TextInput::make('icon'),
                Forms\Components\KeyValue::make('fields'),
                Forms\Components\Toggle::make('has_archive')->default(true),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('slug')->badge(),
                Tables\Columns\IconColumn::make('has_archive')->boolean(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomPostTypes::route('/'),
            'create' => Pages\CreateCustomPostType::route('/create'),
            'edit' => Pages\EditCustomPostType::route('/{record}/edit'),
        ];
    }
}