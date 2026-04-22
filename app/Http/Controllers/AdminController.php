<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Inventory;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{



    // ... index, storeUser, destroyUser methods ...

    /**
     * Display the Inventory List
     */
    public function inventoryIndex()
    {
        $items = Inventory::orderBy('expiration_date', 'asc')->get();
        return view('admin.inventory', compact('items'));
    }

    /**
     * Show the Create Inventory Page
     */
    public function inventoryCreate()
    {
        return view('admin.inventory_create');
    }

    /**
     * Store New Inventory Item
     */
    public function storeInventory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_level' => 'required|integer|min:0',
            'unit' => 'required|string',
            'min_stock' => 'required|integer|min:0',
            'date_added' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:date_added',
        ]);

        $item = Inventory::create($validated);

        if (class_exists(AuditLog::class)) {
            AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'Added Inventory',
                'details' => "Added {$item->stock_level} {$item->unit} of {$item->name}",
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Item added to inventory successfully.');
    }


}