<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'email',
        'item_id',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function scopesearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }
        return $query->where('name', 'like', '%' . $term . '%');
    } 

}

