<?php

namespace Plugins\Marketing\Filament\Resources\GooglePlacesSearchResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\GooglePlacesSearchResource;
use Illuminate\Database\Eloquent\Builder;
use Plugins\Marketing\Models\MarketingContact;

class ListGooglePlacesSearches extends ListRecords
{
    protected static string $resource = GooglePlacesSearchResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getTableQuery(): ?Builder
    {
        // Return empty query since this is a utility page
        return MarketingContact::query()->whereRaw('1 = 0'); // Always empty
    }
}