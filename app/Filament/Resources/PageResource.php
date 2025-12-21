<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageResource\Pages;
use App\Filament\Resources\PageResource\RelationManagers;
use App\Models\Page;
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
use App\Filament\Forms\Components\AiContentGenerator;
use Filament\Resources\Concerns\Translatable;

class PageResource extends Resource
{
    use Translatable;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 5;
    protected static ?string $model = Page::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Page Management')
                    ->tabs([
                        // TAB 1: Content
                        Tabs\Tab::make('Content')
                            ->icon('heroicon-o-document-text')
                            ->schema([Section::make('Page Identification')
                                    ->schema([
                                        Forms\Components\TextInput::make('slug')
                                            ->required()
                                            ->unique(ignoreRecord: true)
                                            ->maxLength(255)
                                            ->helperText('URL-friendly identifier'),
                                    ])
                                    ->columns(1),

                                Section::make('Page Content')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->label('Page Title')
                            ->required()
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                if (empty($get('slug'))) {
                                    $set('slug', \Str::slug($state));
                                }
                            })
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('excerpt')
                            ->label('Page Excerpt')
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief page summary'),
                        
                        TinyEditor::make('content')
                            ->label('Page Content')
                            ->required()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('uploads')
                            ->profile('full')
                            ->minHeight(500)
                            ->columnSpanFull(),
                    ]),
                            ]),

                        // TAB 2: Media & Publishing
                        Tabs\Tab::make('Publishing')
                            ->icon('heroicon-o-calendar')
                            ->schema([
                                Section::make('Media')
                                    ->schema([
                                        Forms\Components\FileUpload::make('featured_image')
                                            ->image()
                                            ->directory('pages')
                                            ->maxSize(2048)
                                            ->helperText('Recommended size: 1200x630px'),
                                    ]),

                                Section::make('Publishing')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'published' => 'Published',
                                    'scheduled' => 'Scheduled',
                                ])
                                ->default('draft')
                                ->required(),
                            
                            Forms\Components\Toggle::make('is_homepage')
                                ->label('Set as Homepage')
                                ->helperText('Mark this as the site homepage'),
                            
                            Forms\Components\Toggle::make('show_in_menu')
                                ->label('Show in Menu')
                                ->default(true)
                                ->helperText('Display in navigation menus'),
                        ]),
                        
                        Grid::make(2)->schema([
                            Forms\Components\DateTimePicker::make('published_at')
                                ->label('Publish Date')
                                ->default(now())
                                ->helperText('Leave empty to publish immediately'),
                            
                            Forms\Components\TextInput::make('order')
                                ->numeric()
                                ->default(0)
                                ->helperText('Menu display order (lower numbers first)'),
                        ]),
                    ]),
                            ]),

                        // TAB 3: Template & Menus
                        Tabs\Tab::make('Template & Menus')
                            ->icon('heroicon-o-squares-2x2')
                            ->schema([
                                Section::make('Template & Display')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('user_id')
                                ->relationship('user', 'name')
                                ->default(auth()->id())
                                ->required()
                                ->label('Author'),
                            
                            Forms\Components\Select::make('template_id')
                                ->relationship('template', 'name')
                                ->searchable()
                                ->preload()
                                ->helperText('Choose a template layout'),
                        ]),
                        
                        Forms\Components\CheckboxList::make('menu_locations')
                            ->label('Display in Menus')
                            ->options(fn() => \App\Models\Menu::where('is_active', true)->pluck('name', 'id'))
                            ->columns(2)
                            ->helperText('Select which menus this page should appear in'),
                    ]),
                            ]),

                        // TAB 4: SEO
                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Section::make('SEO')
                    ->schema([
                        Forms\Components\TextInput::make('meta_title')
                            ->maxLength(60)
                            ->helperText('Recommended: 50-60 characters'),
                        
                        Forms\Components\Textarea::make('meta_description')
                            ->rows(3)
                            ->maxLength(160)
                            ->helperText('Recommended: 150-160 characters'),
                        
                        Forms\Components\Textarea::make('meta_keywords')
                            ->rows(2)
                            ->helperText('Comma-separated keywords'),
                        
                        Forms\Components\TextInput::make('canonical_url')
                            ->label('Canonical URL')
                            ->url()
                            ->placeholder('https://carphatian.ro/page-slug')
                            ->helperText('Preferred URL for this page'),
                        
                        Forms\Components\Select::make('robots_meta')
                            ->label('Robots Meta Tag')
                            ->options([
                                'index,follow' => 'Index & Follow (Default)',
                                'noindex,follow' => 'No Index, Follow',
                                'index,nofollow' => 'Index, No Follow',
                                'noindex,nofollow' => 'No Index, No Follow',
                            ])
                            ->default('index,follow'),
                    ]),
                            ]),

                        // TAB 5: Custom Fields
                        Tabs\Tab::make('Custom Fields')
                            ->icon('heroicon-o-code-bracket')
                            ->schema([
                                Section::make('Custom Fields')
                                    ->description('Add custom fields for advanced template customization')
                                    ->schema([
                                        Forms\Components\Repeater::make('custom_fields')
                                            ->schema([
                                                Forms\Components\TextInput::make('key')
                                                    ->label('Field Name')
                                                    ->required()
                                                    ->placeholder('e.g., background_color')
                                                    ->columnSpan(1),
                                                
                                                Forms\Components\Select::make('type')
                                                    ->label('Type')
                                                    ->options([
                                                        'text' => 'Text',
                                                        'url' => 'URL',
                                                        'email' => 'Email',
                                                        'number' => 'Number',
                                                        'boolean' => 'Yes/No',
                                                        'color' => 'Color',
                                                    ])
                                                    ->default('text')
                                                    ->reactive()
                                                    ->columnSpan(1),
                                                
                                                Forms\Components\Textarea::make('value')
                                                    ->label('Field Value')
                                                    ->rows(2)
                                                    ->placeholder('Enter value...')
                                                    ->columnSpan(2),
                                            ])
                                            ->columns(4)
                                            ->addActionLabel('Add Custom Field')
                                            ->defaultItems(0)
                                            ->collapsible()
                                            ->itemLabel(fn (array $state): ?string => $state['key'] ?? 'New Field')
                                            ->columnSpanFull(),
                                    ]),
                            ]),

                                Section::make('🪄 AI Content Generator')
                                    ->description('Generate all content fields above using AI')
                                    ->schema([
                                        AiContentGenerator::make('ai_generator')
                                            ->targetFields(['title', 'content', 'meta_title', 'meta_description', 'meta_keywords'])
                                            ->contentType('page')
                                            ->columnSpanFull(),
                                    ])
                                    ->collapsed()
                                    ->collapsible(),
                            ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Page Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn ($record) => $record->slug)
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? reset($state)) : $state)
                    ->wrap(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'scheduled' => 'info',
                        default => 'gray'
                    }),
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Homepage')
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->trueColor('success'),
                Tables\Columns\IconColumn::make('show_in_menu')
                    ->label('In Menu')
                    ->boolean(),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ]),
                Tables\Filters\TernaryFilter::make('is_homepage')
                    ->label('Homepage'),
                Tables\Filters\TernaryFilter::make('show_in_menu')
                    ->label('Show in Menu'),
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
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'published', 'published_at' => now()]);
                            \Filament\Notifications\Notification::make()
                                ->title('Pages published')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('draft')
                        ->label('Set as Draft')
                        ->icon('heroicon-o-document')
                        ->color('warning')
                        ->action(function ($records) {
                            $records->each->update(['status' => 'draft']);
                            \Filament\Notifications\Notification::make()
                                ->title('Pages set as draft')
                                ->success()
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
            ->defaultSort('id', 'desc')
            ->paginationPageOptions([10, 25, 50])
            ->defaultPaginationPageOption(25)
            ->extremePaginationLinks()
            ->persistFiltersInSession()
            ->persistSearchInSession();
    }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('messages.cms');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPages::route('/'),
            'create' => Pages\CreatePage::route('/create'),
            'edit' => Pages\EditPage::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'draft')->count();
    }

    public static function getTranslatableLocales(): array
    {
        return ['en', 'ro', 'de', 'fr', 'es', 'it'];
    }
}
