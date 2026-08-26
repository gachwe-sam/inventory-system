<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'expiry_date',
        'unit_price',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'unit_price' => 'float',
    ];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function stock()
    {
        return $this->hasMany(Branchstock::class);
    }

    public function totalQuantity(): float
    {
        return $this->stock->sum('quantity');
    }


    public function scopesearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }
        return $query->where(function ($q) use ($term) {
            $q->where('name','like','%'. $term .'%')->orWhere('description','like','%'. $term .'%');
        });
    }
    // app/Models/Item.php
public function scopeInCategory(Builder $query, ?int $categoryId): Builder
{
    if (! $categoryId) {
        return $query;
    }

    $category = Category::find($categoryId);

    return $category
        ? $query->whereIn('category_id', $category->descendantAndSelfIds())
        : $query;
}
// app/Models/Item.php
public function scopeLowStock(Builder $query, bool $only): Builder
{
    return $only
        ? $query->whereHas('stock', fn ($q) => $q->whereColumn('quantity', '<=', 'reorder_level'))
        : $query;
}

}