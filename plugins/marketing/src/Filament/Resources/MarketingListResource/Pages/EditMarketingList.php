<?php

namespace Plugins\Marketing\Filament\Resources\MarketingListResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Plugins\Marketing\Filament\Resources\MarketingListResource;

class EditMarketingList extends EditRecord
{
    protected static string $resource = MarketingListResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make(),
        ];
    }
}
