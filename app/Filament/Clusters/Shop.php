<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Shop extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 20;
    
    public static function getNavigationLabel(): string
    {
        return __('Magazin');
    }
}
