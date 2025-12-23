<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Communications;

use App\Filament\Resources\EmailTemplateResource\Pages;
use App\Models\EmailTemplate;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmailTemplateResource extends Resource
{
    protected static ?string $model = EmailTemplate::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?string $cluster = Communications::class;
    protected static ?int $navigationSort = 1;

    public static function getNavigationLabel(): string
    {
        return __('Email Templates');
    }

    public static function getModelLabel(): string
    {
        return __('Email Template');
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
                    Forms\Components\TextInput::make('subject')
                        ->label(__('Subject'))
                        ->required()
                        ->maxLength(255)
                        ->helperText(__('Use {{variable}} for placeholders')),
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('Active'))
                        ->default(true),
                ])->columns(2),
            
            Forms\Components\Section::make(__('Template Content'))
                ->schema([
                    Forms\Components\Textarea::make('body_html')
                        ->label(__('HTML Body'))
                        ->required()
                        ->rows(15)
                        ->helperText(__('Available variables: {{user_name}}, {{app_name}}, etc.')),
                    Forms\Components\Textarea::make('body_text')
                        ->label(__('Plain Text Body'))
                        ->rows(5),
                ]),
            
            Forms\Components\Section::make(__('Variables'))
                ->schema([
                    Forms\Components\TagsInput::make('variables')
                        ->label(__('Available Variables'))
                        ->placeholder(__('Add variable'))
                        ->helperText(__('Variables that can be used in this template')),
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
                    ->searchable()
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('subject')
                    ->label(__('Subject'))
                    ->limit(40),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Active'))
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label(__('Active')),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label(__('Preview'))
                    ->icon('heroicon-o-eye')
                    ->modalContent(fn (EmailTemplate $record) => view('filament.email-preview', ['template' => $record]))
                    ->modalHeading(fn (EmailTemplate $record) => $record->name),
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
            'index' => Pages\ListEmailTemplates::route('/'),
            'create' => Pages\CreateEmailTemplate::route('/create'),
            'edit' => Pages\EditEmailTemplate::route('/{record}/edit'),
        ];
    }
}
