<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
   protected $fillable = [
        'customer_name',
        'product_name',
        'quantity',
        'price', // Added
        'total', // Added
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}