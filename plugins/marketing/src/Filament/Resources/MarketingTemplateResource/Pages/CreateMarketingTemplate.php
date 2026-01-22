<?php

namespace Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\Marketing\Filament\Resources\MarketingTemplateResource;

class CreateMarketingTemplate extends CreateRecord
{
    protected static string $resource = MarketingTemplateResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
