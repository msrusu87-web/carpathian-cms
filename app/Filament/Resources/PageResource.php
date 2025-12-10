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
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

class PageResource extends Resource
{

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'CMS';

    protected static ?int $navigationSort = 5;
    protected static ?string $model = Page::class;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Page Content')
                    ->schema([
                        Grid::make(2)->schema([
                            Forms\Components\TextInput::make('title')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Str::slug($state)))
                                ->maxLength(255),
                            
                            Forms\Components\TextInput::make('slug')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),
                        ]),
                        
                        TinyEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->fileAttachmentsDisk('public')
                            ->fileAttachmentsDirectory('uploads')
                            ->profile('full')
                            ->minHeight(500),
                        
                        Forms\Components\Textarea::make('excerpt')
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull()
                            ->helperText('Brief page summary (optional)'),
                    ]),

                Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('featured_image')
                            ->image()
                            ->directory('pages')
                            ->maxSize(2048)
                            ->helperText('Recommended size: 1200x630px'),
                    ])
                    ->collapsible(),

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
                    ])
                    ->collapsible(),

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
                    ])
                    ->collapsible()
                    ->collapsed(),

                Section::make('Custom Fields')
                    ->schema([
                        Forms\Components\KeyValue::make('custom_fields')
                            ->keyLabel('Field Name')
                            ->valueLabel('Field Value')
                            ->helperText('Add custom fields for template use'),
                    ])
                    ->collapsible()
                    ->collapsed(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('featured_image')
                    ->label('Image')
                    ->circular(),
                
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->limit(30)
                    ->copyable(),
                
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'secondary' => 'draft',
                        'success' => 'published',
                        'warning' => 'scheduled',
                    ])
                    ->icons([
                        'heroicon-o-pencil' => 'draft',
                        'heroicon-o-check-circle' => 'published',
                        'heroicon-o-clock' => 'scheduled',
                    ]),
                
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('template.name')
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->default('Default'),
                
                Tables\Columns\IconColumn::make('is_homepage')
                    ->label('Homepage')
                    ->boolean()
                    ->trueIcon('heroicon-o-home')
                    ->falseIcon('heroicon-o-document')
                    ->trueColor('success'),
                
                Tables\Columns\IconColumn::make('show_in_menu')
                    ->label('In Menu')
                    ->boolean(),
                
                Tables\Columns\TextColumn::make('order')
                    ->numeric()
                    ->sortable()
                    ->alignEnd(),
                
                Tables\Columns\TextColumn::make('published_at')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('order', 'asc')
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
                
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('view_page')
                    ->label('View on Site')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Page $record): string => url('/' . $record->slug))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    
                    Tables\Actions\BulkAction::make('publish')
                        ->label('Publish Selected')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['status' => 'published', 'published_at' => now()])),
                    
                    Tables\Actions\BulkAction::make('hide_from_menu')
                        ->label('Hide from Menu')
                        ->icon('heroicon-o-eye-slash')
                        ->requiresConfirmation()
                        ->action(fn ($records) => $records->each->update(['show_in_menu' => false])),
                ]),
            ]);
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
}
