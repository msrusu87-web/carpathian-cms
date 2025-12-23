<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class UsersPermissions extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 90;
    
    public static function getNavigationLabel(): string
    {
        return __('users_permissions');
    }
}
