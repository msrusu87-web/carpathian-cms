<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MenuResource\Pages;
use App\Models\Menu;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MenuResource extends Resource

{
    public static function getNavigationGroup(): ?string
    {
        return __('CMS');
    }

    public static function getNavigationLabel(): string
    {
        return __('Menus');
    }


    protected static ?string $navigationIcon = 'heroicon-o-bars-3';
    protected static ?string $navigationGroup = 'Design';

    
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Menu::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Menu Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                                                ->label(__('Name'))
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        
                        Forms\Components\Select::make('location')
                            ->label('Menu Location')
                            ->options(Menu::getLocations())
                            ->required()
                            ->helperText('Choose where this menu should appear on your site'),
                        
                        Forms\Components\TextInput::make('order')
                                                ->label(__('Order'))
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        
                        Forms\Components\Textarea::make('description')
                                                ->label(__('Description'))
                            ->rows(3)
                            ->columnSpanFull(),
                        
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
                Tables\Columns\TextColumn::make('name')
                                        ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('location')
                    ->formatStateUsing(fn (string $state): string => Menu::getLocations()[$state] ?? $state)
                    ->colors([
                        'primary' => 'top',
                        'success' => fn ($state) => in_array($state, ['top_left', 'top_right']),
                        'warning' => 'footer',
                        'info' => fn ($state) => in_array($state, ['sidebar_left', 'sidebar_right']),
                    ]),
                
                Tables\Columns\TextColumn::make('allItems_count')
                    ->label('Items')
                    ->counts('allItems')
                    ->badge(),
                
                Tables\Columns\ToggleColumn::make('is_active')
                    ->label('Active'),
                
                Tables\Columns\TextColumn::make('order')
                                        ->label(__('Order'))
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('created_at')
                                        ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('location')
                    ->options(Menu::getLocations()),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active')
                    ->placeholder('All menus')
                    ->trueLabel('Active menus')
                    ->falseLabel('Inactive menus'),
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
            'index' => Pages\ListMenus::route('/'),
            'create' => Pages\CreateMenu::route('/create'),
            'edit' => Pages\EditMenu::route('/{record}/edit'),
        ];
    }
}
