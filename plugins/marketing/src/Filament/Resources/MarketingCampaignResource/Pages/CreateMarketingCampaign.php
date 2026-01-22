<?php

namespace Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Plugins\Marketing\Filament\Resources\MarketingCampaignResource;
use Illuminate\Support\Facades\Log;

class CreateMarketingCampaign extends CreateRecord
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        Log::info('CreateMarketingCampaign: mutateFormDataBeforeCreate', ['data' => $data]);
        return $data;
    }

    protected function afterCreate(): void
    {
        Log::info('CreateMarketingCampaign: afterCreate', ['record' => $this->record->toArray()]);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
