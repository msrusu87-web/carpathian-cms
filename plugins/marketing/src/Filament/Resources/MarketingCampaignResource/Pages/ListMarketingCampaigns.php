<?php

namespace Plugins\Marketing\Filament\Resources\MarketingCampaignResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Plugins\Marketing\Filament\Resources\MarketingCampaignResource;
use Plugins\Marketing\Models\MarketingRateLimit;

class ListMarketingCampaigns extends ListRecords
{
    protected static string $resource = MarketingCampaignResource::class;

    protected function getHeaderActions(): array
    {
        return [
            \Filament\Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \Plugins\Marketing\Filament\Widgets\RateLimitWidget::class,
        ];
    }
}
