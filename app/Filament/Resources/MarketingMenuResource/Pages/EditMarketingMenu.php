<?php

namespace App\Filament\Resources\MarketingMenuResource\Pages;

use App\Filament\Resources\MarketingMenuResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions\DeleteAction;

class EditMarketingMenu extends EditRecord
{
    protected static string $resource = MarketingMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}