<?php

namespace Plugins\Marketing\Filament\Resources\MarketingListResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingListResource;

class ListMarketingLists extends ListRecords
{
    protected static string $resource = MarketingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
