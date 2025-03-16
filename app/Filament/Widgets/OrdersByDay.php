<?php

namespace App\Filament\Widgets;

use App\Models\Item;
use Filament\Widgets\Widget;
use Illuminate\Support\Carbon;

class OrdersByDay extends Widget
{
    protected static string $view = 'filament.widgets.orders-by-day';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Orders for Today';

    public function getViewData(): array
    {
        $today = strtolower(Carbon::today()->format('l')); // e.g., "thursday"

        $itemsByDay = Item::query()
            ->select('items.name', 'items.must_have', 'items.count', 'items.unit', 'items.note', 'suppliers.order_day')
            ->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
            ->where('suppliers.order_day', $today)
            ->get()
            ->groupBy('order_day');

        return [
            'itemsByDay' => $itemsByDay,
        ];
    }
}
