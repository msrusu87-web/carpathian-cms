<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Design;

use App\Filament\Resources\WidgetResource\Pages;
use App\Models\Widget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Resources\Concerns\Translatable;

class WidgetResource extends Resource
{
    use Translatable;
    
    protected static ?string $model = Widget::class;
    protected static ?string $cluster = Design::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';
    
    
    protected static ?int $navigationSort = 1;
    
    public static function getNavigationLabel(): string
    {
        return __('Widgets');
    }
    
    public static function getPluralLabel(): ?string
    {
        return __('Widgets');
    }
    
    public static function getLabel(): ?string
    {
        return __('Widget');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Widget Details')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->label(__('Title'))
                                    ->maxLength(255),
                                    
                                Forms\Components\Select::make('type')
                                    ->label(__('Type'))
                                    ->required()
                                    ->options([
                                        'hero' => 'Hero',
                                        'features' => 'Features',
                                        'products' => 'Products',
                                        'blog' => 'Blog',
                                        'footer' => 'Footer',
                                        'custom' => 'Custom',
                                    ]),
                                    
                                Forms\Components\TextInput::make('order')
                                    ->label(__('Order'))
                                    ->required()
                                    ->numeric()
                                    ->default(0),
                                    
                                Forms\Components\Select::make('status')
                                    ->label(__('Status'))
                                    ->required()
                                    ->options([
                                        'active' => 'Active',
                                        'inactive' => 'Inactive',
                                    ])
                                    ->default('active'),
                            ]),
                            
                        Forms\Components\Textarea::make('content')
                            ->label(__('Content (JSON)'))
                            ->rows(10)
                            ->columnSpanFull()
                            ->helperText('Enter widget content as JSON format'),
                            
                        Forms\Components\Textarea::make('settings')
                            ->label(__('Settings (JSON)'))
                            ->rows(5)
                            ->columnSpanFull()
                            ->helperText('Enter widget settings as JSON format'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')
                    ->label(__('Type'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hero' => 'success',
                        'features' => 'info',
                        'products' => 'warning',
                        'blog' => 'primary',
                        'footer' => 'gray',
                        default => 'secondary',
                    })
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('title')
                    ->label(__('Title'))
                    ->searchable()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('order')
                    ->label(__('Order'))
                    ->numeric()
                    ->sortable(),
                    
                Tables\Columns\TextColumn::make('status')
                    ->label(__('Status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
                    
                Tables\Columns\TextColumn::make('updated_at')
                    ->label(__('Updated'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('order', 'asc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label(__('Type'))
                    ->options([
                        'hero' => 'Hero',
                        'features' => 'Features',
                        'products' => 'Products',
                        'blog' => 'Blog',
                        'footer' => 'Footer',
                    ]),
                    
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
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
            ]);
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
