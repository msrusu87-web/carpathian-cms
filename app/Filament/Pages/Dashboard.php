<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $view = 'filament.pages.dashboard';
    
    public static function getNavigationLabel(): string
    {
        return __('messages.dashboard');
    }
    
    public static function getNavigationGroup(): ?string
    {
        return null;
    }
    
    public function getTitle(): string
    {
        return __('messages.dashboard');
    }
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Widgets\StatsOverview::class,
        ];
    }

    public function getColumns(): int | string | array
    {
        return 2;
    }
}
