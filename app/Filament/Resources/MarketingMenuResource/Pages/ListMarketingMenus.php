<?php

namespace App\Filament\Resources\MarketingMenuResource\Pages;

use App\Filament\Resources\MarketingMenuResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;

class ListMarketingMenus extends ListRecords
{
    protected static string $resource = MarketingMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}