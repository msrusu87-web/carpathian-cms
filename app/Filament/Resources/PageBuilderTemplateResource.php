<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageBuilderTemplateResource\Pages;
use App\Models\PageBuilderTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageBuilderTemplateResource extends Resource
{
    protected static ?string $model = PageBuilderTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Design';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
                Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
                Forms\Components\Select::make('category')->options(['hero' => 'Hero', 'features' => 'Features', 'footer' => 'Footer'])->required(),
                Forms\Components\Textarea::make('content')->rows(10),
                Forms\Components\FileUpload::make('thumbnail')->image(),
                Forms\Components\Toggle::make('is_active')->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('category')->badge(),
                Tables\Columns\ImageColumn::make('thumbnail')->circular(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPageBuilderTemplates::route('/'),
            'create' => Pages\CreatePageBuilderTemplate::route('/create'),
            'edit' => Pages\EditPageBuilderTemplate::route('/{record}/edit'),
        ];
    }
}