<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;
    protected $fillable = [
        "name","description","item_id","email",
    ];

    public function purchase()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
    public function item()
    {
        return $this->hasMany(Item::class);
    }
    
}

