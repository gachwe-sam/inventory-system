<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    protected $table = 'subcategories';

    protected $fillable = ['name'];

    public function items()
    {
        return $this->hasMany(Item::class, 'subcategory_id');
    }
}
