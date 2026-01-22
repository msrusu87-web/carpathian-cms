<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use App\Filament\Resources\MarketingMenuResource\Pages;

class MarketingMenuResource extends Resource
{
    protected static ?string $model = \Plugins\Marketing\Models\MarketingContact::class;
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?string $navigationLabel = 'MARKETING MENU';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Marketing Tools';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Contact Name')
                ->maxLength(255),
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255),
            Forms\Components\TextInput::make('phone')
                ->tel()
                ->maxLength(50),
            Forms\Components\TextInput::make('company')
                ->maxLength(255),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('email')->searchable()->sortable(),
                TextColumn::make('phone')->searchable(),
                TextColumn::make('company')->searchable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarketingMenus::route('/'),
            'create' => Pages\CreateMarketingMenu::route('/create'),
            'edit' => Pages\EditMarketingMenu::route('/{record}/edit'),
        ];
    }
}