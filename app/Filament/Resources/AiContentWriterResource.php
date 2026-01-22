<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AiContentWriterResource\Pages;
use App\Models\AiGeneration;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Http;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;

class AiContentWriterResource extends Resource
{
    protected static ?string $model = AiGeneration::class;
    
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    
    protected static ?string $navigationLabel = 'AI Content Writer';
    
    protected static ?string $navigationGroup = 'AI';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        $languages = [
            'en' => 'English',
            'ro' => 'Română',
            'de' => 'Deutsch',
            'fr' => 'Français',
            'es' => 'Español',
            'it' => 'Italiano',
        ];

        return $form
            ->schema([
                Section::make('Content Type')
                    ->schema([
                        Forms\Components\Select::make('type')
                            ->label('What do you want to create?')
                            ->options([
                                'page' => '📄 Page',
                                'blog' => '📝 Blog Post',
                                'product' => '🛍️ Product',
                                'widget' => '🧩 Widget',
                                'seo' => '🎯 SEO Content',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(fn ($state, callable $set) => $set('parameters', [])),
                    ]),

                Section::make('AI Generation Settings')
                    ->schema([
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('model')
                                ->label('AI Model')
                                ->options([
                                    'groq' => 'Groq (Fast)',
                                    'openai' => 'OpenAI GPT-4',
                                    'anthropic' => 'Claude',
                                ])
                                ->default('groq')
                                ->required(),
                            
                            Forms\Components\Select::make('tone')
                                ->label('Writing Tone')
                                ->options([
                                    'professional' => 'Professional',
                                    'casual' => 'Casual',
                                    'friendly' => 'Friendly',
                                    'persuasive' => 'Persuasive',
                                    'informative' => 'Informative',
                                ])
                                ->default('professional'),
                            
                            Forms\Components\Select::make('target_language')
                                ->label('Primary Language')
                                ->options($languages)
                                ->default('ro'),
                        ]),
                    ]),

                // Page Specific Fields
                Section::make('Page Details')
                    ->schema([
                        Forms\Components\TextInput::make('page_topic')
                            ->label('Page Topic/Title')
                            ->placeholder('e.g., About Us, Services, Contact')
                            ->required(),
                        
                        Forms\Components\Textarea::make('page_description')
                            ->label('Page Description/Brief')
                            ->placeholder('Describe what this page should contain...')
                            ->rows(3),
                        
                        Grid::make(2)->schema([
                            Forms\Components\Select::make('page_template')
                                ->label('Page Template')
                                ->options([
                                    'default' => 'Default',
                                    'landing' => 'Landing Page',
                                    'services' => 'Services Page',
                                    'about' => 'About Us',
                                    'contact' => 'Contact',
                                ]),
                            
                            Forms\Components\Toggle::make('include_seo')
                                ->label('Generate SEO Meta Tags')
                                ->default(true),
                        ]),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'page'),

                // Blog Post Specific Fields
                Section::make('Blog Post Details')
                    ->schema([
                        Forms\Components\TextInput::make('blog_title')
                            ->label('Blog Post Title/Topic')
                            ->placeholder('e.g., Top 10 Web Design Trends 2025')
                            ->required(),
                        
                        Forms\Components\Textarea::make('blog_keywords')
                            ->label('Keywords/Focus')
                            ->placeholder('web design, trends, 2025, responsive')
                            ->rows(2),
                        
                        Grid::make(3)->schema([
                            Forms\Components\Select::make('blog_category')
                                ->label('Category')
                                ->relationship('category', 'name', fn ($query) => $query->where('type', 'blog'))
                                ->searchable()
                                ->preload(),
                            
                            Forms\Components\TextInput::make('blog_word_count')
                                ->label('Target Word Count')
                                ->numeric()
                                ->default(800)
                                ->suffix('words'),
                            
                            Forms\Components\Toggle::make('include_images_suggestions')
                                ->label('Suggest Images')
                                ->default(true),
                        ]),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'blog'),

                // Product Specific Fields
                Section::make('Product Details')
                    ->schema([
                        Forms\Components\TextInput::make('product_name')
                            ->label('Product Name')
                            ->placeholder('e.g., E-Commerce Website Package')
                            ->required(),
                        
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('product_price')
                                ->label('Price')
                                ->numeric()
                                ->prefix('RON'),
                            
                            Forms\Components\Select::make('product_category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->searchable()
                                ->preload(),
                        ]),
                        
                        Forms\Components\Textarea::make('product_features')
                            ->label('Key Features (one per line)')
                            ->placeholder("Responsive design\nSEO optimized\n24/7 support")
                            ->rows(4),
                        
                        Forms\Components\Toggle::make('generate_variants')
                            ->label('Generate Product Variants')
                            ->default(false),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'product'),

                // Widget Specific Fields
                Section::make('Widget Details')
                    ->schema([
                        Forms\Components\TextInput::make('widget_name')
                            ->label('Widget Name')
                            ->placeholder('e.g., Testimonials Carousel')
                            ->required(),
                        
                        Forms\Components\Select::make('widget_type')
                            ->label('Widget Type')
                            ->options([
                                'hero' => 'Hero Section',
                                'features' => 'Features Grid',
                                'testimonials' => 'Testimonials',
                                'cta' => 'Call to Action',
                                'stats' => 'Statistics Counter',
                                'team' => 'Team Members',
                            ]),
                        
                        Forms\Components\Textarea::make('widget_content')
                            ->label('Widget Content Description')
                            ->rows(3),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'widget'),

                // SEO Specific Fields
                Section::make('SEO Details')
                    ->schema([
                        Forms\Components\TextInput::make('seo_target_url')
                            ->label('Target Page URL')
                            ->placeholder('https://carphatian.ro/services'),
                        
                        Forms\Components\Textarea::make('seo_keywords')
                            ->label('Target Keywords')
                            ->placeholder('web design romania, dezvoltare website, SEO')
                            ->rows(2),
                        
                        Forms\Components\TextInput::make('seo_competitor_urls')
                            ->label('Competitor URLs (optional)')
                            ->placeholder('https://competitor1.com, https://competitor2.com'),
                    ])
                    ->visible(fn (Forms\Get $get) => $get('type') === 'seo'),

                Section::make('Main Prompt')
                    ->schema([
                        Forms\Components\Textarea::make('prompt')
                            ->label('Custom Instructions (Optional)')
                            ->placeholder('Add any specific requirements or instructions for the AI...')
                            ->rows(4)
                            ->helperText('Leave blank to use auto-generated prompt based on fields above'),
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Generated Content')
                    ->schema([
                        Tabs::make('Generated Languages')
                            ->tabs(collect($languages)->map(fn($label, $locale) => 
                                Tabs\Tab::make($label)
                                    ->schema([
                                        Forms\Components\TextInput::make("generated_title_{$locale}")
                                            ->label('Title')
                                            ->placeholder('AI generated title will appear here'),
                                        
                                        Forms\Components\Textarea::make("generated_excerpt_{$locale}")
                                            ->label('Excerpt/Meta Description')
                                            ->rows(2),
                                        
                                        TinyEditor::make("generated_content_{$locale}")
                                            ->label('Content')
                                            ->profile('full')
                                            ->fileAttachmentsDisk('public')
                                            ->fileAttachmentsDirectory('ai-content')
                                            ->minHeight(400)
                                            ->columnSpanFull(),
                                    ])
                            )->toArray()),
                    ])
                    ->visible(fn (Forms\Get $get) => filled($get('response'))),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
                
                Forms\Components\Hidden::make('status')
                    ->default('pending'),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\IconColumn::make('type')
                    ->label('Type')
                    ->icon(fn (string $state): string => match ($state) {
                        'page' => 'heroicon-o-document-text',
                        'blog' => 'heroicon-o-newspaper',
                        'product' => 'heroicon-o-shopping-bag',
                        'widget' => 'heroicon-o-squares-2x2',
                        'seo' => 'heroicon-o-magnifying-glass',
                        default => 'heroicon-o-document',
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'page' => 'info',
                        'blog' => 'warning',
                        'product' => 'success',
                        'widget' => 'purple',
                        'seo' => 'primary',
                        default => 'gray',
                    }),
                
                Tables\Columns\TextColumn::make('type')
                    ->badge()
                    ->searchable()
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                
                Tables\Columns\TextColumn::make('prompt')
                    ->limit(50)
                    ->searchable()
                    ->tooltip(fn ($record) => $record->prompt),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'pending',
                        'warning' => 'processing',
                        'success' => 'completed',
                        'danger' => 'failed',
                    ]),
                
                Tables\Columns\TextColumn::make('model')
                    ->badge()
                    ->color('info'),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Created By')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('tokens_used')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'page' => 'Page',
                        'blog' => 'Blog',
                        'product' => 'Product',
                        'widget' => 'Widget',
                        'seo' => 'SEO',
                    ]),
                
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'processing' => 'Processing',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('generate')
                    ->label('Generate with AI')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->visible(fn ($record) => $record->status !== 'completed')
                    ->action(function ($record) {
                        // AI generation logic here
                        Notification::make()
                            ->title('AI Generation Started')
                            ->success()
                            ->send();
                    }),
                
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                
                Tables\Actions\Action::make('create_content')
                    ->label('Create Content')
                    ->icon('heroicon-o-document-plus')
                    ->color('primary')
                    ->visible(fn ($record) => $record->status === 'completed')
                    ->action(function ($record) {
                        // Logic to create actual page/blog/product from AI content
                        Notification::make()
                            ->title('Content Created Successfully')
                            ->success()
                            ->send();
                    }),
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
            'index' => Pages\ListAiContentWriters::route('/'),
            'create' => Pages\CreateAiContentWriter::route('/create'),
            'edit' => Pages\EditAiContentWriter::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'completed')->count();
    }
}
