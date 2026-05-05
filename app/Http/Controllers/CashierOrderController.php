<?php

namespace App\Http\Controllers;

use App\Models\Order;
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
        // Validate the order inputs
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_name' => 'required|array',
            'product_name.*' => 'string|max:255',
            'price' => 'required|array',              // Validate price array
            'price.*' => 'numeric|min:0',            // Validate individual prices
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ]);

        // Create orders for each product
        foreach ($request->product_name as $index => $productName) {
            $quantity = $request->quantity[$index];
            $price = $request->price[$index];
            $total = $quantity * $price; // Calculate total for this item

            Order::create([
                'customer_name' => $request->customer_name,
                'product_name' => $productName,
                'price' => $price,           // Store the unit price
                'quantity' => $quantity,
                'total' => $total,           // Store the total cost
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('cashier.orders')
            ->with('success', 'Order created and sent to kitchen.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('cashier.orders')
            ->with('success', 'Order deleted successfully.');
    }
}
