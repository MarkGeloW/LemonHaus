<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{
    public function index()
    {
        $stocks = Inventory::latest()->get();
        $products = Product::latest()->get();

        return view('admin.inventory', compact('stocks', 'products'));
    }
}