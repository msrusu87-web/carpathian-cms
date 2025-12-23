<?php

namespace App\Filament\Resources;
use App\Filament\Clusters\Blog;

use App\Filament\Resources\PostResource\Pages;
use App\Models\Post;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use App\Filament\Forms\Components\AiContentGenerator;
use Filament\Resources\Concerns\Translatable;

class PostResource extends Resource
{
    use Translatable;

    protected static ?string $navigationIcon = 'heroicon-o-newspaper';

    protected static ?string $cluster = Blog::class;
    protected static ?string $navigationLabel = 'Articole';

    protected static ?int $navigationSort = 1;
    protected static ?string $model = Post::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Tabs::make('Post Management')
                    ->tabs([
                        Tabs\Tab::make('Content')

                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\TextInput::make('slug')
                                    ->required()
                                    ->unique(ignoreRecord: true)
                                    ->maxLength(255)
                                    ->helperText('URL-friendly identifier'),

                                Forms\Components\TextInput::make('title')
                                    ->label('Post Title')
                                    ->required()
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                                        if (empty($get('slug'))) {
                                            $set('slug', \Str::slug($state));
                                        }
                                    })
                                    ->maxLength(255),
                                
                                Forms\Components\Textarea::make('excerpt')
                                    ->label('Post Excerpt')
                                    ->rows(3)
                                    ->maxLength(500)
                                    ->helperText('Brief summary of the post'),
                                
                                TinyEditor::make('content')
                                    ->label('Post Content')
                                    ->required()
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('uploads')
                                    ->profile('full')
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('Media & Publishing')
                            ->icon('heroicon-o-photo')
                            ->schema([
                                Forms\Components\FileUpload::make('featured_image')
                                    ->image()
                                    ->directory('posts')
                                    ->maxSize(2048)
                                    ->helperText('Recommended size: 1200x630px')
                                    ->columnSpanFull(),

                                Grid::make(3)->schema([
                                    Forms\Components\Select::make('status')
                                        ->options([
                                            'draft' => 'Draft',
                                            'published' => 'Published',
                                            'scheduled' => 'Scheduled',
                                        ])
                                        ->default('draft')
                                        ->required()
                                        ->helperText('Post publication status'),
                                    
                                    Forms\Components\Toggle::make('is_featured')
                                        ->label('Featured Post')
                                        ->helperText('Show in featured sections'),
                                    
                                    Forms\Components\Toggle::make('allow_comments')
                                        ->label('Allow Comments')
                                        ->default(true)
                                        ->helperText('Enable comments for this post'),
                                ]),
                                
                                Forms\Components\DateTimePicker::make('published_at')
                                    ->label('Publish Date')
                                    ->default(now())
                                    ->helperText('Leave empty to publish immediately'),
                            ]),

                        Tabs\Tab::make('Categories & Tags')
                            ->icon('heroicon-o-tag')
                            ->schema([
                                Forms\Components\Select::make('category_id')
                                    ->relationship('category', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->helperText('Select post category')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('slug')->required(),
                                        Forms\Components\Textarea::make('description'),
                                    ])
                                    ->columnSpanFull(),
                            
                                Forms\Components\Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->default(auth()->id())
                                    ->required()
                                    ->label('Author')
                                    ->helperText('Post author')
                                    ->columnSpanFull(),
                                
                                Forms\Components\Select::make('tags')
                                    ->relationship('tags', 'name')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->helperText('Select or create tags for this post')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')->required(),
                                        Forms\Components\TextInput::make('slug')->required(),
                                    ])
                                    ->columnSpanFull(),
                            ]),

                        Tabs\Tab::make('SEO')
                            ->icon('heroicon-o-magnifying-glass')
                            ->schema([
                                Forms\Components\TextInput::make('meta_title')
                                    ->maxLength(60)
                                    ->helperText('Recommended: 50-60 characters for optimal display')
                                    ->columnSpanFull(),
                                
                                Forms\Components\Textarea::make('meta_description')
                                    ->rows(3)
                                    ->maxLength(160)
                                    ->helperText('Recommended: 150-160 characters for optimal display')
                                    ->columnSpanFull(),
                                
                                Forms\Components\Textarea::make('meta_keywords')
                                    ->rows(2)
                                    ->helperText('Comma-separated keywords for search engines')
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
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('')
                    ->circular()
                    ->size(40)
                    ->defaultImageUrl(url('/images/placeholder-post.png')),
                Tables\Columns\TextColumn::make('title')
                    ->label('Post Title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->description(fn ($record) => $record->category?->name)
                    ->formatStateUsing(fn ($state) => is_array($state) ? ($state[app()->getLocale()] ?? reset($state)) : $state)
                    ->wrap(),
                Tables\Columns\TextColumn::make('author.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match($state) {
                        'published' => 'success',
                        'draft' => 'warning',
                        'scheduled' => 'info',
                        default => 'gray'
                    }),
                Tables\Columns\TextColumn::make('published_at')
                    ->label('Published')
                    ->dateTime('d M Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')
                    ->relationship('category', 'name')
                    ->label('Category')
                    ->preload(),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'published' => 'Published',
                        'scheduled' => 'Scheduled',
                    ]),
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
                                ->title('Posts published')
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
                                ->title('Posts set as draft')
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
