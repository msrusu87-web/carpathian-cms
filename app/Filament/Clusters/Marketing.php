<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class Marketing extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-megaphone';
    protected static ?int $navigationSort = 45;
    protected static ?string $navigationLabel = 'Marketing';
}
