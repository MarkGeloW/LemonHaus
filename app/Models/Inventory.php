<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'name',
        'category',
        'stock_level',
        'unit',
        'min_stock',
        'date_added',
        'expiration_date',
    ];

    protected $casts = [
        'date_added' => 'date',
        'expiration_date' => 'date',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'inventory_product')
            ->withPivot('quantity_used_per_order')
            ->withTimestamps();
    }
    
}