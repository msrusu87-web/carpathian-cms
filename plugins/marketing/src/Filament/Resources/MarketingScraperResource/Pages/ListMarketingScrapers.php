<?php

namespace Plugins\Marketing\Filament\Resources\MarketingScraperResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingScraperResource;

class ListMarketingScrapers extends ListRecords
{
    protected static string $resource = MarketingScraperResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make()
                ->label('New Scrape Job'),
        ];
    }
}
