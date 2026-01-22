<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingList;

class MarketingListResource extends Resource
{
    protected static ?string $model = MarketingList::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Lists';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('List Details')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => 
                            $set('slug', \Illuminate\Support\Str::slug($state))),
                    Forms\Components\TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->rows(3)
                        ->columnSpanFull(),
                    Forms\Components\Toggle::make('is_active')
                        ->default(true)
                        ->label('Active'),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('contact_count')
                    ->label('Contacts')
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('Active'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('viewContacts')
                    ->label('View Contacts')
                    ->icon('heroicon-o-users')
                    ->url(fn (MarketingList $record) => 
                        MarketingContactResource::getUrl('index', ['tableFilters[lists][values][0]' => $record->id])),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => \Plugins\Marketing\Filament\Resources\MarketingListResource\Pages\ListMarketingLists::route('/'),
            'create' => \Plugins\Marketing\Filament\Resources\MarketingListResource\Pages\CreateMarketingList::route('/create'),
            'edit' => \Plugins\Marketing\Filament\Resources\MarketingListResource\Pages\EditMarketingList::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
