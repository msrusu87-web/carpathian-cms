<?php

namespace App\Filament\Resources\TestMarketingResource\Pages;

use App\Filament\Resources\TestMarketingResource;
use Filament\Resources\Pages\ListRecords;

class ListTestMarketings extends ListRecords
{
    protected static string $resource = TestMarketingResource::class;
}