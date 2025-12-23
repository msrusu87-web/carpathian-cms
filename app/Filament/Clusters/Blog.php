<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Blog extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    protected static ?int $navigationSort = 40;
    
    public static function getNavigationLabel(): string
    {
        return __('messages.blog');
    }
}
