<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'supplier_id',
        'must_have',
        'we_have',
        'to_order',
        'unit',
        'note',
        'status',
        'date_submitted',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
