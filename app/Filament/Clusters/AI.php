<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class AI extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-sparkles';
    protected static ?int $navigationSort = 20;
    
    public static function getNavigationLabel(): string
    {
        return __('ai_tools');
    }
}
