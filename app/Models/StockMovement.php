<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    const UPDATED_AT = null; // YOU CAN'T EDIT A LEDGER TABLE SO IT ONLY CREATED AT NO UPDATED AT 
    
    protected $fillable = [
        'branch_id',
        'item_id',
        'quantity_change',
        'type',
        'reference_id',
        'user_id',
        'notes',
    ];
    protected $casts = [
        'quantity_change'=>'float',
        'created_at'=> 'datetime',
    ];
    public function branch()
        {
            return $this->belongsTo(Branch::class);
        }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
