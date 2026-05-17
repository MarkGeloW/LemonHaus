<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderRecord extends Model
{
    protected $table = 'order_records';

    protected $fillable = [
        'order_id',
        'customer_name',
        'product_name',
        'quantity',
        'price',
        'total',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];
}