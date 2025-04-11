<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SupplierResource\Pages;
use App\Filament\Resources\SupplierResource\RelationManagers;
use App\Models\Supplier;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class SupplierResource extends Resource
{
    protected static ?string $model = Supplier::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Suppliers';
    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Select::make('order_day')
                    ->options([
                        'monday' => 'Monday',
                        'tuesday' => 'Tuesday',
                        'wednesday' => 'Wednesday',
                        'thursday' => 'Thursday',
                        'friday' => 'Friday',
                        'saturday' => 'Saturday',
                        'sunday' => 'Sunday',
                    ])
                    ->required()
                    ->searchable(), // Optional: allows searching through options
                TextInput::make('username')
                    ->required()
                    ->maxLength(255),
                TextInput::make('url')
                    ->label('Website URL')
                    ->url()
                    ->maxLength(255),
                Forms\Components\TextInput::make('password')
                    ->password(),
                TextInput::make('contact_info')
                    ->required()
                    ->maxLength(255),
                TextInput::make('note')
                    ->maxLength(500),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('password'),
                TextColumn::make('order_day')->sortable()->formatStateUsing(fn ($state) => ucfirst($state ?? '')),
                TextColumn::make('username')->sortable()->searchable(),
                TextColumn::make('contact_info'),
                TextColumn::make('url')
                    ->label('Website')
                    ->url(fn ($record) => $record->url, true)
                    ->openUrlInNewTab()
                    ->placeholder('-'),
                TextColumn::make('note')->limit(50)->placeholder('-'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Action::make('visitWebsite')
                    ->label('Visit Website')
                    ->url(fn ($record) => $record->url)
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('info')
                    ->visible(fn ($record) => !empty($record->url)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Add relation manager for Items if needed
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSuppliers::route('/'),
            'create' => Pages\CreateSupplier::route('/create'),
            'edit' => Pages\EditSupplier::route('/{record}/edit'),
        ];
    }
}
