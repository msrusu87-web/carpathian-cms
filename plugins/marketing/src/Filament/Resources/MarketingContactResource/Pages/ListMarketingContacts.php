<?php

namespace Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingContactResource;

class ListMarketingContacts extends ListRecords
{
    protected static string $resource = MarketingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
