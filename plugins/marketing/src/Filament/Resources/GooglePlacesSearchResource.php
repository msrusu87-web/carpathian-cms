<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use App\Filament\Clusters\Marketing;
use Filament\Notifications\Notification;
use Plugins\Marketing\Models\MarketingList;
use Plugins\Marketing\Services\GooglePlacesService;
use Plugins\Marketing\Filament\Resources\GooglePlacesSearchResource\Pages;

class GooglePlacesSearchResource extends Resource
{
    protected static ?string $model = null; // No actual model, it's a utility resource
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?string $navigationLabel = 'Google Places Search';
    protected static ?string $slug = 'google-places-search';
    protected static ?string $cluster = Marketing::class;
    protected static ?int $navigationSort = 25;

    public static function form(Form $form): Form
    {
        return $form->schema(self::getSearchFormSchema());
    }

    /**
     * Get the form schema for the search action
     */
    protected static function getSearchFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Location Settings')
                ->schema([
                    Select::make('city')
                        ->label('City')
                        ->options(array_combine(
                            array_keys(GooglePlacesService::getRomanianCities()),
                            array_keys(GooglePlacesService::getRomanianCities())
                        ))
                        ->default('București')
                        ->required()
                        ->live(),
                    
                    Forms\Components\Grid::make(2)
                        ->schema([
                            TextInput::make('latitude')
                                ->label('Latitude')
                                ->numeric()
                                ->step(0.000001)
                                ->default(44.4268)
                                ->required(),
                            
                            TextInput::make('longitude')
                                ->label('Longitude')
                                ->numeric()
                                ->step(0.000001)
                                ->default(26.1025)
                                ->required(),
                        ]),
                    
                    TextInput::make('radius')
                        ->label('Search Radius (meters)')
                        ->numeric()
                        ->default(1000)
                        ->helperText('Maximum 50,000 meters (50km)')
                        ->required(),
                ]),

            Forms\Components\Section::make('Business Filters')
                ->schema([
                    Select::make('business_type')
                        ->label('Business Type')
                        ->options(GooglePlacesService::getBusinessTypes())
                        ->default('establishment')
                        ->required(),
                    
                    TextInput::make('keyword')
                        ->label('Keyword (Optional)')
                        ->helperText('e.g., "pizza", "hotel", "dentist"'),
                ]),

            Forms\Components\Section::make('Import Settings')
                ->schema([
                    Select::make('list_id')
                        ->label('Add to Marketing List (Optional)')
                        ->options(MarketingList::pluck('name', 'id'))
                        ->helperText('Contacts will be added to this list automatically'),
                    
                    Textarea::make('notes')
                        ->label('Import Notes')
                        ->helperText('These notes will help you remember this import'),
                ])
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('info')
                    ->label('Search Google Places')
                    ->default('Use the "New Search" button to search for businesses')
                    ->html()
            ])
            ->headerActions([
                Action::make('search')
                    ->label('New Search')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->form(self::getSearchFormSchema())
                    ->action(function (array $data) {
                        try {
                            $service = new GooglePlacesService();
                            
                            // Update coordinates if city changed
                            if ($data['city']) {
                                $cities = GooglePlacesService::getRomanianCities();
                                if (isset($cities[$data['city']])) {
                                    $data['latitude'] = $cities[$data['city']]['lat'];
                                    $data['longitude'] = $cities[$data['city']]['lng'];
                                }
                            }
                            
                            $result = $service->searchAndCreateContacts(
                                lat: $data['latitude'],
                                lng: $data['longitude'],
                                radius: min($data['radius'], 50000), // Cap at 50km
                                type: $data['business_type'],
                                keyword: $data['keyword'] ?? null,
                                listId: $data['list_id'] ?? null
                            );

                            $stats = $result['stats'];
                            
                            Notification::make()
                                ->title('Google Places Search Complete')
                                ->body("Found {$stats['total_places']} places. Created {$stats['created']} new contacts, updated {$stats['updated']} existing contacts.")
                                ->success()
                                ->duration(10000)
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Search Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalWidth('2xl')
                    ->slideOver()
            ])
            ->actions([])
            ->emptyStateHeading('Google Places Business Search')
            ->emptyStateDescription('Search for businesses using Google Places API and automatically create marketing contacts.')
            ->emptyStateIcon('heroicon-o-map-pin')
            ->emptyStateActions([
                Action::make('search')
                    ->label('Start Searching')
                    ->icon('heroicon-o-magnifying-glass')
                    ->color('primary')
                    ->form(self::getSearchFormSchema())
                    ->action(function (array $data) {
                        try {
                            $service = new GooglePlacesService();
                            
                            // Update coordinates if city changed
                            if ($data['city']) {
                                $cities = GooglePlacesService::getRomanianCities();
                                if (isset($cities[$data['city']])) {
                                    $data['latitude'] = $cities[$data['city']]['lat'];
                                    $data['longitude'] = $cities[$data['city']]['lng'];
                                }
                            }
                            
                            $result = $service->searchAndCreateContacts(
                                lat: $data['latitude'],
                                lng: $data['longitude'], 
                                radius: min($data['radius'], 50000),
                                type: $data['business_type'],
                                keyword: $data['keyword'] ?? null,
                                listId: $data['list_id'] ?? null
                            );

                            $stats = $result['stats'];
                            
                            Notification::make()
                                ->title('Google Places Search Complete')
                                ->body("Found {$stats['total_places']} places. Created {$stats['created']} new contacts, updated {$stats['updated']} existing contacts.")
                                ->success()
                                ->duration(10000)
                                ->send();

                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Search Failed')
                                ->body('Error: ' . $e->getMessage())
                                ->danger()
                                ->send();
                        }
                    })
                    ->modalWidth('2xl')
                    ->slideOver()
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGooglePlacesSearches::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // This is a utility resource, not a CRUD resource
    }
}
