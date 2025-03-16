<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Supplier;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class InventoryImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $currentSection = null;

        foreach ($rows as $row) {
            // Detect section headers
            if (isset($row['unnamed_1']) && $row['unnamed_1'] === 'Dashboard') {
                $currentSection = 'items';
                continue;
            } elseif (isset($row['unnamed_2']) && $row['unnamed_2'] === 'Suppliers') {
                $currentSection = 'suppliers';
                continue;
            }

            // Skip empty rows
            if (empty($row['unnamed_1']) && empty($row['unnamed_2'])) {
                continue;
            }

            // Process Items section
            if ($currentSection === 'items' && isset($row['category']) && !empty($row['category'])) {
                $category = Category::firstOrCreate(
                    ['category_name' => $row['category']],
                    ['category_name' => $row['category']]
                );

                $supplier = Supplier::firstOrCreate(
                    ['name' => $row['supplier']],
                    [
                        'name' => $row['supplier'],
                        'username' => Str::slug($row['supplier']) . '@mangos.com',
                        'password' => bcrypt('password'),
                    ]
                );

                Item::updateOrCreate(
                    [
                        'category_id' => $category->id,
                        'supplier_id' => $supplier->id,
                        'name' => $row['name'],
                    ],
                    [
                        'must_have' => $row['must_have'] ?? 0,
                        'unit' => $row['unit'] ?? '',
                        'note' => $row['note'] ?? null,
                        'count' => $row['must_have'] ?? 0,
                        'last_count_date' => now(),
                    ]
                );
            }

            // Process Suppliers section
            if ($currentSection === 'suppliers' && isset($row['suppliers']) && !empty($row['suppliers'])) {
                Supplier::updateOrCreate(
                    ['name' => $row['suppliers']],
                    [
                        'url' => $row['url'] ?? null,
                        'username' => $row['username'] ?? null,
                        'password' => bcrypt($row['password'] ?? 'password'),
                        'note' => $row['note'] ?? null,
                    ]
                );
            }
        }
    }

    public function headingRow(): int
    {
        return 1; // Adjust based on the actual header row
    }
}
