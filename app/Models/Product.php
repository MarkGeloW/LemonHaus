<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'price',
        'stock',
        'status',
    ];

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'inventory_product')
            ->withPivot('quantity_used_per_order')
            ->withTimestamps();
    }

    
}