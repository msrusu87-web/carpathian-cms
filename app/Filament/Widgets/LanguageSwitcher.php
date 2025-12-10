<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class LanguageSwitcher extends Widget
{
    protected static string $view = 'filament.widgets.language-switcher';
    
    protected int | string | array $columnSpan = 'full';
    
    protected static ?int $sort = -1;
    
    public static function canView(): bool
    {
        return true;
    }
}
