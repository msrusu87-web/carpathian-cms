<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Notifications\Notification;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingScrapeJob;
use Plugins\Marketing\Models\MarketingList;
use Plugins\Marketing\Services\WebScraperService;

class MarketingScraperResource extends Resource
{
    protected static ?string $model = MarketingScrapeJob::class;
    protected static ?string $navigationIcon = 'heroicon-o-globe-alt';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Web Scraper';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Scrape Job Configuration')
                ->schema([
                    Forms\Components\TextInput::make('name')
                        ->label('Job Name')
                        ->required()
                        ->maxLength(255)
                        ->default(fn () => 'Scrape Job ' . now()->format('Y-m-d H:i')),
                    Forms\Components\Textarea::make('urls')
                        ->label('URLs to Scrape')
                        ->helperText('Enter one URL per line. The scraper will extract contact information from these websites.')
                        ->rows(10)
                        ->required()
                        ->columnSpanFull(),
                ])->columns(1),

            Forms\Components\Section::make('Options')
                ->schema([
                    Forms\Components\Select::make('add_to_list')
                        ->label('Add Contacts to List')
                        ->options(MarketingList::where('is_active', true)->pluck('name', 'id'))
                        ->helperText('Automatically add scraped contacts to this list'),
                    Forms\Components\Toggle::make('skip_duplicates')
                        ->label('Skip Duplicate Emails')
                        ->default(true),
                ])->columns(2),

            Forms\Components\Section::make('Job Status')
                ->schema([
                    Forms\Components\Placeholder::make('status_display')
                        ->label('Status')
                        ->content(fn (?MarketingScrapeJob $record) => $record?->status ?? 'New'),
                    Forms\Components\Placeholder::make('progress_display')
                        ->label('Progress')
                        ->content(fn (?MarketingScrapeJob $record) => 
                            $record ? "{$record->processed_urls}/{$record->total_urls} URLs ({$record->progress}%)" : 'N/A'),
                    Forms\Components\Placeholder::make('contacts_found_display')
                        ->label('Contacts Found')
                        ->content(fn (?MarketingScrapeJob $record) => $record?->contacts_found ?? 0),
                ])
                ->columns(3)
                ->visible(fn (?MarketingScrapeJob $record) => $record !== null),
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
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'gray' => 'pending',
                        'info' => 'running',
                        'success' => 'completed',
                        'danger' => ['failed', 'cancelled'],
                    ]),
                Tables\Columns\TextColumn::make('total_urls')
                    ->label('URLs')
                    ->sortable(),
                Tables\Columns\TextColumn::make('processed_urls')
                    ->label('Processed')
                    ->sortable(),
                Tables\Columns\TextColumn::make('contacts_found')
                    ->label('Contacts')
                    ->sortable()
                    ->badge()
                    ->color('success'),
                Tables\Columns\TextColumn::make('progress')
                    ->label('Progress')
                    ->suffix('%')
                    ->sortable(),
                Tables\Columns\TextColumn::make('started_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'running' => 'Running',
                        'completed' => 'Completed',
                        'failed' => 'Failed',
                        'cancelled' => 'Cancelled',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('run')
                    ->label('Start')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->visible(fn (MarketingScrapeJob $record) => $record->status === 'pending')
                    ->action(function (MarketingScrapeJob $record) {
                        $service = new WebScraperService();
                        $result = $service->runJob($record);
                        
                        Notification::make()
                            ->title($result['success'] ? 'Scraping started' : 'Scraping failed')
                            ->body($result['message'])
                            ->color($result['success'] ? 'success' : 'danger')
                            ->send();
                    }),
                Tables\Actions\Action::make('viewData')
                    ->label('View Data')
                    ->icon('heroicon-o-eye')
                    ->url(fn (MarketingScrapeJob $record) => 
                        MarketingScrapedDataResource::getUrl('index', ['tableFilters[job_id][value]' => $record->id])),
                Tables\Actions\EditAction::make()
                    ->visible(fn (MarketingScrapeJob $record) => $record->status === 'pending'),
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
            'index' => \Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages\ListMarketingScrapers::route('/'),
            'create' => \Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages\CreateMarketingScraper::route('/create'),
            'edit' => \Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages\EditMarketingScraper::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return \App\Models\Plugin::where('slug', 'marketing')->where('is_active', true)->exists();
    }
}
