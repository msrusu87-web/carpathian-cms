<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Design extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';
    protected static ?int $navigationSort = 60;
    
    public static function getNavigationLabel(): string
    {
        return __('messages.design');
    }
}
