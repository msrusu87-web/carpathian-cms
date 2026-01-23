<?php

namespace App\Filament\Resources;

use App\Filament\Clusters\CMS;
use App\Filament\Resources\PortfolioResource\Pages;
use App\Models\Portfolio;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class PortfolioResource extends Resource
{
    protected static ?string $cluster = CMS::class;
    
    protected static ?string $model = Portfolio::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    
    protected static ?string $navigationGroup = 'Content';
    
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Basic Information')
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug($state))),
                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('client')
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->options([
                                'web_development' => 'Web Development',
                                'ai_platform' => 'AI Platform',
                                'blockchain' => 'Blockchain',
                                'web_tools' => 'Web Tools',
                                'openai' => 'OpenAI Integration',
                                'ai_powered' => 'AI Powered',
                                'mobile_app' => 'Mobile App',
                                'ecommerce' => 'E-Commerce',
                            ])
                            ->default('web_development')
                            ->required(),
                        Forms\Components\TextInput::make('website_url')
                            ->url()
                            ->maxLength(255),
                        Forms\Components\DatePicker::make('completion_date'),
                    ])->columns(2),

                Forms\Components\Section::make('Description')
                    ->schema([
                        Forms\Components\Textarea::make('short_description')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->helperText('Brief description shown on portfolio cards (max 500 chars)'),
                        Forms\Components\RichEditor::make('full_description')
                            ->columnSpanFull()
                            ->helperText('Full project description with details, challenges, solutions'),
                    ]),

                Forms\Components\Section::make('Technologies & Services')
                    ->schema([
                        Forms\Components\TagsInput::make('technologies')
                            ->placeholder('Add technology...')
                            ->helperText('e.g., Laravel, Tailwind CSS, MySQL, Docker'),
                        Forms\Components\TagsInput::make('services')
                            ->placeholder('Add service...')
                            ->helperText('e.g., Web Design, Branding, Logo Design, VPS Configuration'),
                    ])->columns(2),

                Forms\Components\Section::make('Media')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->image()
                            ->directory('portfolio')
                            ->helperText('Main portfolio image'),
                        Forms\Components\FileUpload::make('gallery')
                            ->multiple()
                            ->image()
                            ->directory('portfolio/gallery')
                            ->helperText('Additional project images'),
                    ])->columns(2),

                Forms\Components\Section::make('Styling')
                    ->schema([
                        Forms\Components\Select::make('gradient_from')
                            ->options([
                                'purple-600' => 'Purple',
                                'indigo-600' => 'Indigo',
                                'blue-600' => 'Blue',
                                'green-600' => 'Green',
                                'emerald-900' => 'Emerald Dark',
                                'pink-600' => 'Pink',
                                'red-600' => 'Red',
                                'orange-600' => 'Orange',
                                'amber-700' => 'Amber',
                            ])
                            ->default('purple-600'),
                        Forms\Components\Select::make('gradient_to')
                            ->options([
                                'pink-600' => 'Pink',
                                'purple-600' => 'Purple',
                                'blue-600' => 'Blue',
                                'cyan-600' => 'Cyan',
                                'teal-600' => 'Teal',
                                'amber-700' => 'Amber',
                                'yellow-600' => 'Yellow',
                                'indigo-600' => 'Indigo',
                            ])
                            ->default('pink-600'),
                        Forms\Components\Select::make('badge_color')
                            ->options([
                                'purple-700' => 'Purple',
                                'indigo-700' => 'Indigo',
                                'blue-700' => 'Blue',
                                'green-700' => 'Green',
                                'emerald-900' => 'Emerald Dark',
                                'pink-700' => 'Pink',
                            ])
                            ->default('purple-700'),
                    ])->columns(3),

                Forms\Components\Section::make('Settings')
                    ->schema([
                        Forms\Components\TextInput::make('order')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower numbers appear first'),
                        Forms\Components\Toggle::make('is_featured')
                            ->default(false),
                        Forms\Components\Toggle::make('is_active')
                            ->default(true),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->circular(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'web_development' => 'success',
                        'ai_platform' => 'info',
                        'blockchain' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('completion_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('order')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('category')
                    ->options([
                        'web_development' => 'Web Development',
                        'ai_platform' => 'AI Platform',
                        'blockchain' => 'Blockchain',
                        'web_tools' => 'Web Tools',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active'),
                Tables\Filters\TernaryFilter::make('is_featured'),
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
            ->defaultSort('order', 'asc')
            ->reorderable('order');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPortfolios::route('/'),
            'create' => Pages\CreatePortfolio::route('/create'),
            'edit' => Pages\EditPortfolio::route('/{record}/edit'),
        ];
    }
}
