<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category_id',
        'subcategory_id',
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
