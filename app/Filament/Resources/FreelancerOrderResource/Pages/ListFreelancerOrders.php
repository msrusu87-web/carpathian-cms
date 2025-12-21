<?php

namespace App\Filament\Resources\FreelancerOrderResource\Pages;

use App\Filament\Resources\FreelancerOrderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFreelancerOrders extends ListRecords
{
    protected static string $resource = FreelancerOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
