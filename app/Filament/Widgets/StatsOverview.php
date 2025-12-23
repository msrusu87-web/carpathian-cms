<?php

namespace App\Filament\Widgets;

use App\Models\Page;
use App\Models\Post;
use App\Models\Product;
use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        // Get page views from session data (simple count)
        $pageViews = DB::table('sessions')->count() * 10; // Approximate

        return [
            Stat::make(__('Total Pages'), Page::count())
                ->description(__('Published pages'))
                ->descriptionIcon('heroicon-o-document-text')
                ->color('success')
                ->chart([7, 12, 15, 18, 22, 25, 30]),

            Stat::make(__('Blog Posts'), Post::count())
                ->description(__('Total blog posts'))
                ->descriptionIcon('heroicon-o-newspaper')
                ->color('info')
                ->chart([3, 8, 12, 15, 18, 22, 28]),

            Stat::make(__('Products'), Product::count())
                ->description(__('Shop products'))
                ->descriptionIcon('heroicon-o-shopping-bag')
                ->color('warning')
                ->chart([5, 10, 15, 20, 25, 30, 35]),

            Stat::make(__('Orders'), Order::count())
                ->description(__('Total orders'))
                ->descriptionIcon('heroicon-o-shopping-cart')
                ->color('danger')
                ->chart([1, 3, 5, 8, 12, 15, 20]),

            Stat::make(__('Website Visits'), number_format($pageViews))
                ->description(__('Approximate visits'))
                ->descriptionIcon('heroicon-o-eye')
                ->color('primary')
                ->chart([100, 250, 300, 450, 500, 600, 750]),

            Stat::make(__('Contact Messages'), '0')
                ->description(__('View messages below'))
                ->descriptionIcon('heroicon-o-envelope')
                ->color('success'),
        ];
    }
}
