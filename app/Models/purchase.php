<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class purchase extends Model
{
    use softdeletes;

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
