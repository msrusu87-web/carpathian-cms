<?php

namespace App\Filament\Resources\FreelancerOrderResource\Pages;

use App\Filament\Resources\FreelancerOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFreelancerOrder extends EditRecord
{
    protected static string $resource = FreelancerOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
