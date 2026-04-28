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
            'quantity' => 'required|array',
            'quantity.*' => 'integer|min:1',
        ]);

        // Create orders for each product
        foreach ($request->product_name as $index => $productName) {
            Order::create([
                'customer_name' => $request->customer_name,  // Store the customer name in the order table
                'product_name' => $productName,
                'quantity' => $request->quantity[$index],
                'status' => 'pending',
            ]);
        }

        // Redirect to the orders index page
        return redirect()
            ->route('cashier.orders')
            ->with('success', 'Order created and sent to kitchen.');
    }

    public function destroy($id)
{
    // Find the order by ID and delete it
    $order = Order::findOrFail($id);
    $order->delete();

    // Redirect back to the orders page with success message
    return redirect()->route('cashier.orders')
        ->with('success', 'Order deleted successfully.');
}
}
