<?php

namespace Plugins\Marketing\Filament\Resources\MarketingScrapedDataResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingScrapedDataResource;

class ListMarketingScrapedData extends ListRecords
{
    protected static string $resource = MarketingScrapedDataResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
