<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ItemResource\Pages;
use App\Imports\InventoryImport;
use App\Models\Item;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Toggle;
use Maatwebsite\Excel\Facades\Excel;

class ItemResource extends Resource
{
    protected static ?string $model = Item::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('category_id')
                    ->relationship('category', 'category_name')
                    ->required(),
                Select::make('supplier_id')
                    ->relationship('supplier', 'name')
                    ->required(),
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('must_have')
                    ->required()
                    ->required(),
                TextInput::make('unit')
                    ->required()
                    ->maxLength(50),
                TextInput::make('note')
                    ->nullable()
                    ->maxLength(500),
                FileUpload::make('image')
                    ->label('Image')
                    ->disk('public') // Stores in storage/app/public
                    ->directory('item-images') // Stores in storage/app/public/item-images
                    ->image() // Restricts to image files
                    ->required(false) // Optional field
                    ->preserveFilenames() // Keeps original file names
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif']),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('category.category_name')->sortable()->searchable(),
                TextColumn::make('supplier.name')->sortable(),
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('must_have')->sortable(),
                TextColumn::make('unit')->sortable(),
                TextColumn::make('count')->sortable(),
                TextColumn::make('last_count_date')->date()->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->headerActions([
                \Filament\Tables\Actions\Action::make('import')
                    ->label('Import Inventory')
                    ->icon('heroicon-o-arrow-up-tray')
                    ->form([
                        \Filament\Forms\Components\FileUpload::make('file')
                            ->label('Upload Excel File')
                            ->acceptedFileTypes(['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])
                            ->required()
                            ->disk('public') // Specify the disk where files are stored
                            ->directory('uploads'), // Store in a specific directory
                    ])
                    ->action(function (array $data) {
                        // Get the uploaded file path from the form data
                        $file = $data['file'] ?? null;

                        if (!$file) {
                            throw new \Exception('No file was uploaded.');
                        }

                        // The file is stored automatically by Filament, and $file contains the path
                        $filePath = storage_path('app/public/' . $file);

                        if (!file_exists($filePath)) {
                            throw new \Exception('File not found at: ' . $filePath);
                        }

                        // Import the Excel file
                        Excel::import(new InventoryImport, $filePath);

                        // Optionally clean up the file after import (not always necessary with Filament)
                        // unlink($filePath);

                        return redirect()->back()->with('success', 'Inventory imported successfully!');
                    })
                    ->modalHeading('Import Inventory')
                    ->modalSubmitActionLabel('Import'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListItems::route('/'),
            'create' => Pages\CreateItem::route('/create'),
            'edit' => Pages\EditItem::route('/{record}/edit'),
        ];
    }
}
