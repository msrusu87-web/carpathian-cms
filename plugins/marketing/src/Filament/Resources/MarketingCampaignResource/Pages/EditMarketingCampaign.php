<?php

namespace Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Plugins\Marketing\Filament\Resources\MarketingCampaignResource;

class EditMarketingCampaign extends EditRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\DeleteAction::make()
                ->visible(fn () => $this->record->isDraft()),
        ];
    }
}
