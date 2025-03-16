<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SuppliersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip rows without a supplier name
        if (!isset($row['suppliers']) || empty($row['suppliers'])) {
            return null;
        }

        return Supplier::updateOrCreate(
            ['name' => $row['suppliers']],
            [
                'url' => $row['url'] ?? null,
                'username' => $row['username'] ?? null,
                'password' => bcrypt($row['password'] ?? 'password'), // Ensure passwords are hashed
                'note' => $row['note'] ?? null,
            ]
        );
    }

    public function headingRow(): int
    {
        return 1; // Adjust based on the Suppliers section (row 1 contains headers: Suppliers, URL, UserName, etc.)
    }
}
