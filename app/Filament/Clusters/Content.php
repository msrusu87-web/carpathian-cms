<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Content extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-folder';
    protected static ?int $navigationSort = 80;
    
    public static function getNavigationLabel(): string
    {
        return __('messages.content');
    }
}
