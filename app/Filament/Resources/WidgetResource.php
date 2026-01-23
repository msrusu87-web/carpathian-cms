<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CMS;
use App\Filament\Resources\WidgetResource\Pages;
use App\Models\Widget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class WidgetResource extends Resource
{
    protected static ?string $model = Widget::class;
    protected static ?string $navigationIcon = 'heroicon-o-puzzle-piece';
    protected static ?string $cluster = CMS::class;
    protected static ?int $navigationSort = 10;

    public static function getNavigationLabel(): string
    {
        return __('Widgets');
    }

    public static function getModelLabel(): string
    {
        return __('Widget');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Widget Information'))
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label(__('Title'))
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('type')
                            ->label(__('Widget Type'))
                            ->options([
                                'hero' => __('Hero Section'),
                                'features' => __('Features Section'),
                                'products' => __('Products Section'),
                                'blog' => __('Blog Section'),
                                'footer' => __('Footer'),
                                'copyright' => __('Copyright'),
                                'custom' => __('Custom HTML'),
                            ])
                            ->required(),

                        Forms\Components\Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'active' => __('Active'),
                                'inactive' => __('Inactive'),
                            ])
                            ->default('active')
                            ->required(),

                        Forms\Components\TextInput::make('order')
                            ->label(__('Display Order'))
                            ->numeric()
                            ->default(0),
                    ])
                    ->columns(2),

                Section::make(__('Content'))
                    ->schema([
                        TinyEditor::make('content')
                            ->label(__('Content'))
                            ->columnSpanFull(),

                        Forms\Components\KeyValue::make('settings')
                            ->label(__('Settings'))
                            ->keyLabel(__('Key'))
                            ->valueLabel(__('Value'))
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge(),

                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\TextColumn::make('order')
                    ->label(__('Order'))
                    ->sortable(),

                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'hero' => __('Hero Section'),
                        'features' => __('Features Section'),
                        'products' => __('Products Section'),
                        'blog' => __('Blog Section'),
                        'footer' => __('Footer'),
                    ]),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => __('Active'),
                        'inactive' => __('Inactive'),
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWidgets::route('/'),
            'create' => Pages\CreateWidget::route('/create'),
            'edit' => Pages\EditWidget::route('/{record}/edit'),
        ];
    }
}
