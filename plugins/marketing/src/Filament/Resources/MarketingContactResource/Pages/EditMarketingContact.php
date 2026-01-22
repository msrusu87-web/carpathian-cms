<?php

namespace Plugins\Marketing\Filament\Resources\MarketingContactResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Plugins\Marketing\Filament\Resources\MarketingContactResource;

class EditMarketingContact extends EditRecord
{
    protected static string $resource = MarketingContactResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
