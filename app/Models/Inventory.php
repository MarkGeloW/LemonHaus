<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    // These fields match your form inputs
    protected $fillable = [
        'name',
        'category',
        'stock_level',
        'unit',
        'min_stock',
        'date_added',
        'expiration_date',
    ];

    // This ensures Laravel treats these as Date objects
    protected $casts = [
        'date_added' => 'date',
        'expiration_date' => 'date',
    ];
}