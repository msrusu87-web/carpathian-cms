<?php

namespace Plugins\Marketing\Filament\Resources\MarketingTemplateResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Plugins\Marketing\Filament\Resources\MarketingTemplateResource;

class EditMarketingTemplate extends EditRecord
{
    protected static string $resource = MarketingTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
