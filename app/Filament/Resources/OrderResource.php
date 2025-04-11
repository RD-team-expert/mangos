<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Widgets\OrdersByDay;
use App\Models\Item;
use App\Models\Supplier;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\Action;

class OrderResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationLabel = 'Orders';
//    protected static ?string $group = 'Inventory'; // Corrected and added here
    protected static ?string $model = Item::class;
    protected static ?string $navigationGroup = 'Inventory';
    public static function getWidgets(): array
    {
        return [
            OrdersByDay::class,
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                // Action to visit the supplier's website
                Action::make('visitSupplierWebsite')
                    ->label('Visit Supplier Website')
                    ->button()
                    ->url(function ($livewire) {
                        $supplierId = $livewire->tableFilters['supplier']['value'] ?? null;
                        if ($supplierId) {
                            $supplier = Supplier::find($supplierId);
                            return $supplier?->url ?? '';
                        }
                        return '';
                    })
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square') // Correct icon
                    ->color('info')
                    ->visible(function ($livewire) {
                        $supplierId = $livewire->tableFilters['supplier']['value'] ?? null;
                        return $supplierId && Supplier::find($supplierId)?->url;
                    }),

                // Action to toggle between showing all items and items to purchase
                Action::make('toggleView')
                    ->label(fn () => session('showAllItems', false) ? 'Show Items to Purchase' : 'Show All Items')
                    ->button()
                    ->action(function () {
                        // Toggle the session state
                        session(['showAllItems' => !session('showAllItems', false)]);
                    })
                    ->color('secondary')
                    ->icon('heroicon-o-arrows-right-left'),
            ])
            ->query(
                fn () => Item::query()
                    ->select(
                        'items.name',
                        'items.must_have',
                        'items.unit',
                        'items.count as we_have',
                        'items.note',
                        'items.id as item_id',
                        'categories.category_name',
                        'suppliers.name as supplier_name',
                        'suppliers.url as supplier_url' // Include supplier URL in the query
                    )
                    ->join('categories', 'items.category_id', '=', 'categories.id')
                    ->join('suppliers', 'items.supplier_id', '=', 'suppliers.id')
                    ->when(!session('showAllItems', false), function ($query) {
                        // Show only items that need to be ordered
                        return $query->whereRaw('items.must_have > items.count');
                    })
            )
            ->columns([
                TextColumn::make('category_name')
                    ->label('Category')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('must_have')
                    ->label('Must have')
                    ->sortable(),
                TextColumn::make('unit')
                    ->label('Unit')
                    ->sortable(),
                TextColumn::make('we_have')
                    ->label('We have')
                    ->sortable(),
                TextColumn::make('we_need_to_order')
                    ->label('We need to order')
                    ->getStateUsing(function ($record) {
                        return max(0, (int)$record->must_have - (int)$record->we_have);
                    })
                    ->sortable()
                    ->color('success'),
                TextColumn::make('supplier_name')
                    ->label('Supplier')
                    ->sortable(),
                TextColumn::make('note')
                    ->label('Note')
                    ->sortable(),
                TextColumn::make('item_id')
                    ->label('ID')
                    ->sortable(),
            ])
            ->filters([
                // Filter by category
                SelectFilter::make('category')
                    ->label('Filter by Category')
                    ->relationship('category', 'category_name')
                    ->options(\App\Models\Category::pluck('category_name', 'id')->toArray())
                    ->preload()
                    ->query(function ($query, $state) {
                        if ($state['value']) {
                            $query->where('categories.id', $state['value']);
                        }
                    }),

                // Filter by supplier
                SelectFilter::make('supplier')
                    ->label('Filter by Supplier')
                    ->relationship('supplier', 'name')
                    ->options(\App\Models\Supplier::pluck('name', 'id')->toArray())
                    ->preload()
                    ->query(function ($query, $state) {
                        if ($state['value']) {
                            $query->where('suppliers.id', $state['value']);
                        }
                    }),
            ])
            ->actions([
                // Edit action for items
                Tables\Actions\EditAction::make()
                    ->url(fn ($record) => ItemResource::getUrl('edit', ['record' => $record->item_id])),
            ])
            ->bulkActions([
                // Bulk delete action
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->paginationPageOptions([10, 25, 50]) // Pagination options
            ->defaultPaginationPageOption(10) // Default pagination
            ->defaultSort('name', 'asc');
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
        ];
    }
}
