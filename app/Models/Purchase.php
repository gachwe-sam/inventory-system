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
        'item_id',
    ];
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
