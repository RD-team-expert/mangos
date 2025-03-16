<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use App\Models\Item;
use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget\Card;

class InventoryOverview extends BaseWidget
{

    protected function getCards(): array
    {
        $totalItems = Item::count();
        $itemsToOrder = Item::whereRaw('must_have > count')->count();
        $totalSuppliers = \App\Models\Supplier::count();

        return [
            Card::make('Total Items', $totalItems)
                ->description('Total items in inventory')
                ->color('success'),
            Card::make('Items to Order', $itemsToOrder)
                ->description('Items that need to be ordered')
                ->color('danger'),
            Card::make('Total Suppliers', $totalSuppliers)
                ->description('Number of suppliers')
                ->color('info'),
        ];
    }
}
