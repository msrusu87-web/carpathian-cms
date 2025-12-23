<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Shop extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 50;
    
    public static function getNavigationLabel(): string
    {
        return __('shop');
    }
}
