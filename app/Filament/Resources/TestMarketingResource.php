<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\TestMarketingResource\Pages;
use Plugins\Marketing\Models\MarketingContact;

class TestMarketingResource extends Resource
{
    protected static ?string $model = MarketingContact::class;
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?string $navigationLabel = 'TEST MARKETING';
    protected static ?int $navigationSort = 99;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('email')->email()->required(),
            Forms\Components\TextInput::make('name'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('email')->searchable(),
                TextColumn::make('name')->searchable(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTestMarketings::route('/'),
            'create' => Pages\CreateTestMarketing::route('/create'),
            'edit' => Pages\EditTestMarketing::route('/{record}/edit'),
        ];
    }
}