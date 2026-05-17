<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Http\Request;

class AdminStockController extends Controller
{
    public function create()
    {
        $products = Product::where('status', 'available')
            ->orderBy('name')
            ->get();

        return view('admin.stocks_create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:100',
            'stock_level' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'min_stock' => 'required|integer|min:0',
            'date_added' => 'required|date',
            'expiration_date' => 'nullable|date|after_or_equal:date_added',
        ]);

    Inventory::create([
    'name' => $request->name,
    'category' => $request->category,
    'stock_level' => $request->stock_level,
    'unit' => $request->unit,
    'min_stock' => $request->min_stock,
    'date_added' => $request->date_added,
    'expiration_date' => $request->expiration_date ?: null,
]);
        return redirect()
            ->route('admin.inventory')
            ->with('success', 'Stock added successfully.');
    }

    public function edit(Inventory $stock)
    {
        return view('admin.stock_edit', compact('stock'));
    }

    public function update(Request $request, Inventory $stock)
    {
      $request->validate([
    'name' => 'required|string|max:255',
    'category' => 'required|string|max:100',
    'stock_level' => 'required|integer|min:0',
    'unit' => 'required|string|max:50',
    'min_stock' => 'required|integer|min:0',
    'date_added' => 'required|date',
    'expiration_date' => 'nullable|date|after_or_equal:date_added',
]);
     $stock->update([
    'name' => $request->name,
    'category' => $request->category,
    'stock_level' => $request->stock_level,
    'unit' => $request->unit,
    'min_stock' => $request->min_stock,
    'date_added' => $request->date_added,
    'expiration_date' => $request->expiration_date ?: null,
]);

        return redirect()
            ->route('admin.inventory')
            ->with('success', 'Stock updated successfully.');
    }

    public function destroy(Inventory $stock)
    {
        $stock->delete();

        return redirect()
            ->route('admin.inventory')
            ->with('success', 'Stock deleted successfully.');
    }
}