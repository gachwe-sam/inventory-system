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

    public function purchase()
{
    return $this->hasMany(PurchaseOrder::class);   // PurchaseOrder doesn't exist anywhere in your app
}
}

