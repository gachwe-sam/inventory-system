<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branchstock extends Model
{
    protected $table = 'branch_stock';
    protected $fillable = ['branch_id','item_id','quantity','reorder_level'];
    protected $casts = [
        'quantity'=> 'float',
        'reorder_level'=> 'float',
    ];
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
