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

        // CHECK INVENTORY AVAILABILITY FOR MENU
        $inventory = Inventory::all();

        $checkStock = function($keyword) use ($inventory) {
            $item = $inventory->first(function($i) use ($keyword) {
                return stripos($i->name, $keyword) !== false;
            });
            return $item ? $item->stock_level : 0;
        };

        $cups = $checkStock('Cup');
        $straws = $checkStock('Straw');
        $lemons = $checkStock('Lemon');
        
        $baseStock = min($cups, $straws, $lemons);

        $drinkAvailability = [
            'Classic Lemonade'    => $baseStock > 0,
            'Mint Lemonade'       => $baseStock > 0 && $checkStock('Mint') > 0,
            'Strawberry Lemonade' => $baseStock > 0 && $checkStock('Strawberry') > 0,
            'Grape Lemonade'      => $baseStock > 0 && $checkStock('Grape') > 0,
        ];

        return view('cashier.orders', compact('orders', 'drinkAvailability'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|array',
            'product_name.*' => 'string|max:255',
            'price' => 'required|array',
            'price.*' => 'numeric|min:0',
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ]);

        foreach ($request->product_name as $index => $productName) {
            $qty = $request->quantity[$index];
            $price = $request->price[$index];

            Order::create([
                'customer_name' => $request->customer_name,
                'product_name' => $productName,
                'quantity' => $qty,
                'price' => $price,
                'total' => $price * $qty,
                'status' => 'pending',
            ]);

            // SMART INVENTORY DEDUCTION
            $deductStock = function($itemName, $amount) {
                $inventoryItem = Inventory::where('name', 'LIKE', '%' . $itemName . '%')->first();
                if ($inventoryItem) {
                    $inventoryItem->decrement('stock_level', $amount);
                }
            };

            $deductStock('Cup', $qty);
            $deductStock('Straw', $qty);
            $deductStock('Lemon', $qty);

            if ($productName === 'Mint Lemonade') {
                $deductStock('Mint', $qty);
            } elseif ($productName === 'Strawberry Lemonade') {
                $deductStock('Strawberry', $qty);
            } elseif ($productName === 'Grape Lemonade') {
                $deductStock('Grape', $qty);
            }
        }

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