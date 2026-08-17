<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'quantity',
        'expiry_date',
        'unit_price',
        'reorder_level',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'quantity' => 'float',
        'unit_price' => 'float',
        'reorder_level' => 'float',
    ];

    /**
     * The category this item is filed under. Should normally be a leaf category
     * (e.g. "Cold Beverage"), not a top-level one like "Beverages" — see Category::isLeaf().
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
