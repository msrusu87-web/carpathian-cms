<?php

namespace Plugins\Marketing\Filament\Resources;

use Filament\Resources\Resource;
use App\Filament\Clusters\Marketing;
use Plugins\Marketing\Models\MarketingContact;

class SimpleMarketingResource extends Resource
{
    protected static ?string $model = MarketingContact::class;
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $cluster = Marketing::class;
    protected static ?string $navigationLabel = 'Simple Test';
    protected static ?int $navigationSort = 1;
    
    public static function getPages(): array
    {
        return [];
    }
}