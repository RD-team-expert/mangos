<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'category_id',
        'supplier_id',
        'name',
        'must_have',
        'unit',
        'note',
        'count',
        'last_count_date',
        'image',
    ];

    /**
     * Get the category that owns the item.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the supplier that owns the item.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Custom method to calculate something related to counting.
     * Renamed to avoid conflict with Eloquent's built-in count() method.
     */
    public function inventoryHistories()
    {
        return $this->hasMany(InventoryHistory::class);
    }
}
