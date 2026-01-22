<?php

namespace App\Filament\Clusters;

use Filament\Clusters\Cluster;

class TestCluster extends Cluster
{
    protected static ?string $navigationIcon = 'heroicon-o-star';
    protected static ?int $navigationSort = 999;
    protected static ?string $navigationLabel = 'TEST MENU';
}