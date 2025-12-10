<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiGenerationResource\Pages;
use App\Filament\Resources\AiGenerationResource\RelationManagers;
use App\Models\AiGeneration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AiGenerationResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationGroup = 'AI';

    protected static ?int $navigationSort = 3;
    protected static ?string $model = AiGeneration::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('prompt')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('response')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('parameters'),
                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(255)
                    ->default('groq'),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\TextInput::make('status')
                    ->required()
                    ->maxLength(255)
                    ->default('pending'),
                Forms\Components\Textarea::make('error_message')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('tokens_used')
                    ->numeric(),
                Forms\Components\TextInput::make('generation_time')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('model')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('tokens_used')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('generation_time')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
        return __('messages.ai');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiGenerations::route('/'),
            'create' => Pages\CreateAiGeneration::route('/create'),
            'edit' => Pages\EditAiGeneration::route('/{record}/edit'),
        ];
    }
}
