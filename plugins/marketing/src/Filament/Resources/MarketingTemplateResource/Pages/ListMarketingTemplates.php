<?php

namespace Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingTemplateResource;

class ListMarketingTemplates extends ListRecords
{
    protected static string $resource = MarketingTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }
}
