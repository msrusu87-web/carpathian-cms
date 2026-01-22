<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GigResource\Pages;
use App\Filament\Resources\GigResource\RelationManagers;
use App\Models\Gig;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GigResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return __('Shop');
    }

    public static function getNavigationLabel(): string
    {
        return __('Services');
    }

    protected static ?string $model = Gig::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
        protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                                        ->label(__('User'))
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('category_id')
                                        ->label(__('Category'))
                    ->numeric(),
                Forms\Components\TextInput::make('title')
                                        ->label(__('Title'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                                        ->label(__('Slug'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                                        ->label(__('Description'))
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('requirements')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('images'),
                Forms\Components\TextInput::make('base_price')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('delivery_days')
                    ->required()
                    ->numeric()
                    ->default(7),
                Forms\Components\TextInput::make('revisions')
                                        ->label(__('Revisions'))
                    ->required()
                    ->numeric()
                    ->default(1),
                Forms\Components\TextInput::make('status')
                                        ->label(__('Status'))
                    ->required(),
                Forms\Components\TextInput::make('views')
                                        ->label(__('Views'))
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('orders_in_queue')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rating')
                    ->required()
                    ->numeric()
                    ->default(0.00),
                Forms\Components\TextInput::make('total_reviews')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user_id')
                                        ->label(__('User'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category_id')
                                        ->label(__('Category'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                                        ->label(__('Title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                                        ->label(__('Slug'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('base_price')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_days')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('revisions')
                                        ->label(__('Revisions'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status'),
                Tables\Columns\TextColumn::make('views')
                                        ->label(__('Views'))
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('orders_in_queue')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rating')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_reviews')
                    ->numeric()
                    ->sortable(),
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
                Tables\Columns\TextColumn::make('deleted_at')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGigs::route('/'),
            'create' => Pages\CreateGig::route('/create'),
            'edit' => Pages\EditGig::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'freelancer')->where('is_active', true)->exists();
    }
}
