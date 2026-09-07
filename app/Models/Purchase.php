<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'email',
    ];
    public function items()
    {
        return $this->belongsToMany(Item::class)
            ->withPivot(['quantity', 'unit_price'])
            ->withTimestamps();
    }
}
