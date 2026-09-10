<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

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

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', '%' . $term . '%')
              ->orWhere('email', 'like', '%' . $term . '%')
              ->orWhereHas('item', function ($q) use ($term) {
                  $q->where('name', 'like', '%' . $term . '%');
              });
        });
    }

}

