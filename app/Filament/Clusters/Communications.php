<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Communications extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    protected static ?int $navigationSort = 70;
    
    public static function getNavigationLabel(): string
    {
        return __('messages.communications');
    }
}
