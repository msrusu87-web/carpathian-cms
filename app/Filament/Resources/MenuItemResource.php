<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\CMS;

use App\Filament\Resources\MenuItemResource\Pages;
use App\Models\MenuItem;
use App\Models\Menu;
use App\Models\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuItemResource extends Resource
{
    public static function getNavigationGroup(): ?string
    {
        return __('CMS');
    }

    public static function getNavigationLabel(): string
    {
        return __('Menu Items');
    }


    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    
    protected static ?int $navigationSort = 3;
    protected static ?string $model = MenuItem::class;
    protected static ?string $cluster = CMS::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Item Details')
                    ->schema([
                        Forms\Components\Select::make('menu_id')
                                               ->label(__('Menu'))
                            ->label('Menu')
                            ->relationship('menu', 'name')
                            ->required()
                            ->preload(),
                        
                        Forms\Components\Select::make('parent_id')
                                               ->label(__('Parent'))
                            ->label('Parent Item')
                            ->relationship('parent', 'title')
                            ->nullable()
                            ->helperText('Leave empty for top-level items'),
                        
                        Forms\Components\TextInput::make('title')
                                                ->label(__('Title'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('type')
                                                ->label(__('Type'))
                            ->options([
                                'custom' => 'Custom Link',
                                'page' => 'Page',
                                'post' => 'Blog Post',
                                'category' => 'Category',
                                'product' => 'Product',
                            ])
                            ->default('custom')
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('url', null)),
                        
                        Forms\Components\TextInput::make('url')
                                               ->label(__('URL'))
                            ->label('URL')
                            ->url()
                            ->visible(fn (callable $get) => $get('type') === 'custom')
                            ->required(fn (callable $get) => $get('type') === 'custom')
                            ->helperText('Enter the full URL (e.g., https://example.com)'),
                        
                        Forms\Components\Select::make('reference_id')
                            ->label('Select Page')
                            ->options(Page::where('status', 'published')->pluck('title', 'id'))
                            ->visible(fn (callable $get) => $get('type') === 'page')
                            ->required(fn (callable $get) => $get('type') === 'page')
                            ->searchable(),
                        
                        Forms\Components\Select::make('target')
                            ->options([
                                '_self' => 'Same Window',
                                '_blank' => 'New Window',
                            ])
                            ->default('_self')
                            ->required(),
                        
                        Forms\Components\TextInput::make('icon')
                                                ->label(__('Icon'))
                            ->helperText('Heroicon name (e.g., heroicon-o-home)'),
                        
                        Forms\Components\TextInput::make('css_class')
                            ->label('CSS Class')
                            ->helperText('Additional CSS classes for styling'),
                        
                        Forms\Components\TextInput::make('order')
                                                ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Toggle::make('is_active')
                                               ->label(__('Active'))
                            ->label('Active')
                            ->default(true)
                            ->inline(false),
                    ])
                    ->columns(2),
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
                
                Tables\Columns\TextColumn::make('menu.name')
                    ->label('Menu')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                
                Tables\Columns\TextColumn::make('parent.title')
                    ->label('Parent')
                    ->default('—'),
                
                Tables\Columns\BadgeColumn::make('type')
                                        ->label(__('Type'))
                    ->colors([
                        'secondary' => 'custom',
                        'success' => 'page',
                        'info' => 'post',
                        'warning' => 'category',
                        'danger' => 'product',
                    ]),
                
                Tables\Columns\TextColumn::make('order')
                                        ->label(__('Order'))
                    ->sortable(),
                
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('created_at')
                                        ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('menu_id')
                    ->label('Menu')
                    ->relationship('menu', 'name'),
                
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'custom' => 'Custom Link',
                        'page' => 'Page',
                        'post' => 'Blog Post',
                        'category' => 'Category',
                        'product' => 'Product',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All items')
                    ->trueLabel('Active items')
                    ->falseLabel('Inactive items'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMenuItems::route('/'),
            'create' => Pages\CreateMenuItem::route('/create'),
            'edit' => Pages\EditMenuItem::route('/{record}/edit'),
        ];
    }
}
