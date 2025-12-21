<?php

namespace App\Filament\Resources\PreSaleRequestResource\Pages;

use App\Filament\Resources\PreSaleRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPreSaleRequest extends EditRecord
{
    protected static string $resource = PreSaleRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
