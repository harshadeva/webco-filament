<?php

namespace App\Filament\Widgets;

use App\Models\Product;
use App\Models\ProductCategory;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?string $pollingInterval = '10s';
    protected static bool $isLazy = true;

    protected function getStats(): array
    {
       return [
            Stat::make('Total Products', Product::count())
                ->description('All products in the system')
                ->color('primary')
                ->icon('heroicon-o-cube'),
            Stat::make('Active Products', Product::where('status', true)->count())
                ->description('Products currently active')
                ->color('success')
                ->icon('heroicon-o-check-circle'),
            Stat::make('Categories', ProductCategory::count())
                ->description('Unique product categories')
                ->color('info')
                ->icon('heroicon-o-folder'),
        ];
    }
}
