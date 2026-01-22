<?php

namespace Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\Marketing\Filament\Resources\MarketingContactResource;

class CreateMarketingContact extends CreateRecord
{
    protected static string $resource = MarketingContactResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
