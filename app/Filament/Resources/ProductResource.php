<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Filament\Forms\Components\AiContentGenerator;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Resources\Concerns\Translatable;

class ProductResource extends Resource
{
    use Translatable;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?int $navigationSort = 1;
    protected static ?string $model = Product::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Product Management')
                    ->tabs([
                        Tabs\Tab::make('Content & Information')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                // AI Content Generator at the TOP
                                Section::make('🪄 AI Content Generator')
                                    ->description('Fill in Product Name below first, then click Generate to create all content automatically')
                                    ->schema([
                                        AiContentGenerator::make('ai_generator')
                                            ->targetFields(['name', 'description', 'content', 'meta_title', 'meta_description', 'meta_keywords'])
                                            ->contentType('product')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible()
                                    ->collapsed(false),

                                Section::make('Basic Information')
                                    ->schema([
                                        Grid::make(2)->schema([
                                            Forms\Components\Select::make('category_id')
                                                ->relationship('category', 'name')
                                                ->searchable()
                                                ->preload()
                                                ->required()
                                                ->helperText('Select product category'),
                                            Forms\Components\TextInput::make('slug')
                                                ->required()
                                                ->unique(ignoreRecord: true)
                                                ->maxLength(255)
                                                ->helperText('URL-friendly identifier'),
                                        ]),
                                        Forms\Components\TextInput::make('name')
                                            ->label('Product Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                                if (empty($get('slug'))) {
                                                    $set('slug', \Str::slug($state));
                                                }
                                            })
                                            ->columnSpanFull(),
                                        TinyEditor::make('description')
                                            ->label('Product Description')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('products')
                                            ->profile('full')
                                            ->required()
                                            ->columnSpanFull()
                                            ->helperText('Complete product description with formatting, images, and media'),
                                    ]),

                                Section::make('Full Content')
                                    ->schema([
                                        TinyEditor::make('content')
                                            ->label('Full Description / Features')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('products')
                                            ->profile('full')
                                            ->columnSpanFull()
                                            ->helperText('Detailed product features, specifications, benefits'),
                                    ])
                                    ->collapsible(),

                                Section::make('SEO Optimization')
                                    ->schema([
                                        Forms\Components\TextInput::make('meta_title')
                                            ->label('Meta Title')
                                            ->maxLength(60)
                                            ->helperText('Recommended: 50-60 characters')
                                            ->columnSpanFull(),
                                        Forms\Components\Textarea::make('meta_description')
                                            ->label('Meta Description')
                                            ->maxLength(160)
                                            ->rows(3)
                                            ->helperText('Recommended: 150-160 characters')
                                            ->columnSpanFull(),
                                        Forms\Components\TextInput::make('meta_keywords')
                                            ->label('Meta Keywords')
                                            ->helperText('Comma-separated keywords')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsible(),
                            ]),

                        Tabs\Tab::make('Inventory & Pricing')
                            ->icon('heroicon-o-currency-dollar')
                            ->schema([
                                Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('sku')
                                        ->label('SKU')
                                        ->required()
                                        ->maxLength(255)
                                        ->helperText('Stock Keeping Unit'),
                                    Forms\Components\TextInput::make('price')
                                        ->required()
                                        ->numeric()
                                        ->prefix('RON')
                                        ->helperText('Regular price'),
                                    Forms\Components\TextInput::make('sale_price')
                                        ->numeric()
                                        ->prefix('RON')
                                        ->helperText('Sale/Discounted price'),
                                ]),
                                Grid::make(3)->schema([
                                    Forms\Components\TextInput::make('stock')
                                        ->required()
                                        ->numeric()
                                        ->default(0)
                                        ->helperText('Available quantity'),
                                    Forms\Components\Toggle::make('is_featured')
                                        ->label('Featured Product')
                                        ->default(false)
                                        ->helperText('Show in featured sections'),
                                    Forms\Components\Toggle::make('is_active')
                                        ->label('Active')
                                        ->default(true)
                                        ->helperText('Product is visible on site'),
                                ]),
                            ]),

                        Tabs\Tab::make('Media')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('images')
                                    ->label('Product Images')
                                    ->image()
                                    ->multiple()
                                    ->directory('products')
                                    ->maxFiles(5)
                                    ->reorderable()
                                    ->helperText('Upload up to 5 images. First image is the main product image.')
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Attributes')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Components\Repeater::make('attributes')
                                    ->label('Product Attributes')
                                    ->schema([
                                        Forms\Components\TextInput::make('name')
                                            ->label('Attribute Name')
                                            ->required()
                                            ->placeholder('e.g., Size, Color, Material')
                                            ->helperText('Name of the attribute'),
                                        Forms\Components\Select::make('type')
                                            ->label('Type')
                                            ->options([
                                                'text' => 'Text',
                                                'select' => 'Dropdown',
                                                'color' => 'Color',
                                                'size' => 'Size',
                                                'number' => 'Number',
                                                'boolean' => 'Yes/No',
                                            ])
                                            ->default('text')
                                            ->reactive(),
                                        Forms\Components\Textarea::make('value')
                                            ->label('Value')
                                            ->placeholder('e.g., Red, XL, Cotton')
                                            ->rows(2)
                                            ->helperText('For multiple options, separate with commas'),
                                    ])
                                    ->columns(3)
                                    ->addActionLabel('Add Attribute')
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['name'] ?? 'New Attribute')
                                    ->columnSpanFull(),

                                Forms\Components\Repeater::make('meta')
                                    ->label('Meta Data')
                                    ->schema([
                                        Forms\Components\TextInput::make('key')
                                            ->label('Meta Key')
                                            ->required()
                                            ->placeholder('e.g., warranty, shipping_weight')
                                            ->helperText('Internal key for meta data'),
                                        Forms\Components\Textarea::make('value')
                                            ->label('Meta Value')
                                            ->required()
                                            ->rows(2)
                                            ->placeholder('e.g., 2 years, 1.5kg'),
                                    ])
                                    ->columns(2)
                                    ->addActionLabel('Add Meta Field')
                                    ->defaultItems(0)
                                    ->collapsible()
                                    ->itemLabel(fn (array $state): ?string => $state['key'] ?? 'New Meta')
                                    ->columnSpanFull(),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(url('/images/placeholder-product.png'))
                    ->getStateUsing(function ($record) {
                        if (!empty($record->images) && is_array($record->images)) {
                            return $record->images[0] ?? null;
                        }
                        return $record->image ?? null;
                    }),
                Tables\Columns\TextColumn::make('name')
                    ->label('Product')
                    ->searchable()
                    ->sortable()
                    ->limit(30)
                    ->description(fn ($record) => $record->sku)
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? reset($state)) : $state)
                    ->wrap(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->searchable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->money('RON')
                    ->sortable(),
                Tables\Columns\TextColumn::make('stock')
                    ->label('Stock')
                    ->badge()
                    ->color(fn ($state) => $state > 10 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Status')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->multiple()
                    ->preload(),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All')
                    ->trueLabel('Active')
                    ->falseLabel('Inactive'),
                Tables\Filters\TernaryFilter::make('is_featured')
                    ->label('Featured')
                    ->placeholder('All')
                    ->trueLabel('Yes')
                    ->falseLabel('No'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->label('Edit')
                    ->button()
                    ->color('primary'),
                Tables\Actions\DeleteAction::make()
                    ->label('')
                    ->button(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('activate')
                        ->label('Activate')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation(false)
                        ->action(function ($records) {
                            $records->each->update(['is_active' => true]);
                            \Filament\Notifications\Notification::make()
                                ->title('Products activated')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('deactivate')
                        ->label('Deactivate')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->requiresConfirmation(false)
                        ->action(function ($records) {
                            $records->each->update(['is_active' => false]);
                            \Filament\Notifications\Notification::make()
                                ->title('Products deactivated')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('feature')
                        ->label('Mark Featured')
                        ->icon('heroicon-o-star')
                        ->color('warning')
                        ->requiresConfirmation(false)
                        ->action(function ($records) {
                            $records->each->update(['is_featured' => true]);
                            \Filament\Notifications\Notification::make()
                                ->title('Products marked as featured')
                                ->success()
                                ->send();
                        })
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\BulkAction::make('export')
                        ->label('Export CSV')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(function ($records) {
                            // Export logic here
                            \Filament\Notifications\Notification::make()
                                ->title('Export started')
                                ->info()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->selectCurrentPageOnly()
            ->recordUrl(null)
            ->recordAction(null)
            ->striped()
            ->defaultSort('id', 'desc')
            ->paginationPageOptions([10, 25, 50, 100])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks()
            ->persistFiltersInSession()
            ->persistSearchInSession()
            ->persistSortInSession();
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.shop');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'ecommerce')->where('is_active', true)->exists();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ro', 'de', 'fr', 'es', 'it'];
    }
}
