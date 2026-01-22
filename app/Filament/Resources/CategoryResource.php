<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Blog;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    public static function getNavigationLabel(): string
    {
        return __('Categories');
    }


    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?string $cluster = Blog::class;

    
    protected static ?int $navigationSort = 2;
    protected static ?string $model = Category::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\TextInput::make('name')
                                                ->label(__('Name'))
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state)))
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('slug')
                                                ->label(__('Slug'))
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                    ]),
                    
                    Forms\Components\Textarea::make('description')
                                            ->label(__('Description'))
                        ->rows(3)
                        ->columnSpanFull(),
                    
                    Forms\Components\Grid::make(2)->schema([
                        Forms\Components\ColorPicker::make('color')
                            ->helperText('Display color for badges'),
                        
                        Forms\Components\Toggle::make('is_active')
                                               ->label(__('Active'))
                            ->label('Active')
                            ->default(true),
                    ]),
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
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                                        ->label(__('Slug'))
                    ->searchable()
                    ->copyable()
                    ->copyMessage('Slug copied!')
                    ->color('gray'),
                
                Tables\Columns\ColorColumn::make('color')
                    ->label('Badge Color'),
                
                Tables\Columns\TextColumn::make('posts_count')
                    ->counts('posts')
                    ->label('Posts')
                    ->badge()
                    ->color('success'),
                
                Tables\Columns\IconColumn::make('is_active')
                                       ->label(__('Active'))
                    ->label('Active')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('created_at')
                                        ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Categories'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => true])),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->action(fn ($records) => $records->each->update(['is_active' => false])),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.blog');
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
