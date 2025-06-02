<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\InventoryHistory;
use Filament\Resources\Resource;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\InventoryHistoryResource\Pages;

class InventoryHistoryResource extends Resource
{
    protected static ?string $model = InventoryHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationLabel = 'Inventory History';
    protected static ?string $navigationGroup = 'Inventory';


    // Restrict access to managers only
    

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                InventoryHistory::query()
                    ->with(['item', 'user'])
                    ->latest('count_date')
                    ->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('item.name')
                    ->label('Item Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('count')
                    ->label('Count')
                    ->sortable(),
                Tables\Columns\TextColumn::make('count_date')
                    ->label('Date')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Updated By')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Note')
                    ->default('N/A')
                    ->searchable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('item')
                    ->relationship('item', 'name')
                    ->label('Filter by Item'),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('Filter by User'),
                Tables\Filters\Filter::make('count_date')
                    ->form([
                        DatePicker::make('count_date_from')
                            ->label('From Date'),
                        DatePicker::make('count_date_to')
                            ->label('To Date'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['count_date_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('count_date', '>=', $date)
                            )
                            ->when(
                                $data['count_date_to'],
                                fn (Builder $query, $date): Builder => $query->whereDate('count_date', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['count_date_from'] ?? null) {
                            $indicators[] = 'From: ' . $data['count_date_from'];
                        }
                        if ($data['count_date_to'] ?? null) {
                            $indicators[] = 'To: ' . $data['count_date_to'];
                        }
                        return $indicators;
                    }),
            ])
            ->actions([
                // No actions needed for a read-only history view
            ])
            ->bulkActions([
                // No bulk actions needed for history
            ])
            ->defaultSort('count_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInventoryHistories::route('/'),
        ];
    }
}