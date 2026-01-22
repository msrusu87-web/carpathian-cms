<?php

namespace Plugins\Marketing\Filament\Resources\MarketingListResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\Marketing\Filament\Resources\MarketingListResource;

class CreateMarketingList extends CreateRecord
{
    protected static string $resource = MarketingListResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
