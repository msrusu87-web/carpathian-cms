<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class CMS extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 30;
    
    public static function getNavigationLabel(): string
    {
        return __('messages.cms');
    }
}
