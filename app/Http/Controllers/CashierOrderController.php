<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Inventory;
use Illuminate\Http\Request;

class CashierOrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();
        return view('cashier.orders', compact('orders'));
    }

    public function store(Request $request)
    {
        // Validate the incoming order
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|array',
            'product_name.*' => 'string|max:255',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ]);

        // Loop through each product the customer ordered
        foreach ($request->product_name as $index => $productName) {
            
            $qty = $request->quantity[$index];
            $price = $request->price[$index];

            // Save the order to the database
            Order::create([
                'customer_name' => $request->customer_name,
                'product_name' => $productName,
                'quantity' => $qty,
                'price' => $price,
                'total' => $price * $qty,
                'status' => 'pending',
            ]);

            // ==========================================
            // SMART INVENTORY DEDUCTION
            // ==========================================
            
            // Helper function to deduct stock safely
            $deductStock = function($itemName, $amount) {
                $inventoryItem = Inventory::where('name', 'LIKE', '%' . $itemName . '%')->first();
                if ($inventoryItem) {
                    $inventoryItem->decrement('stock_level', $amount);
                }
            };

            // 1. STANDARD ITEMS: Every drink gets a Cup, Straw, and Lemon automatically
            $deductStock('Cup', $qty);
            $deductStock('Straw', $qty);
            $deductStock('Lemon', $qty);

            // 2. FLAVOR SPECIFIC ITEMS: Deduct extra ingredients based on the exact dropdown choice
            if ($productName === 'Mint Lemonade') {
                $deductStock('Mint', $qty);
            } elseif ($productName === 'Strawberry Lemonade') {
                $deductStock('Strawberry', $qty);
            } elseif ($productName === 'Grape Lemonade') {
                $deductStock('Grape', $qty);
            }
        }

        // Redirect back with a success message
        return redirect()
            ->route('cashier.orders')
            ->with('success', 'Order created successfully! Ingredients have been automatically deducted from stock.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('cashier.orders')
            ->with('success', 'Order deleted successfully.');
    }
}