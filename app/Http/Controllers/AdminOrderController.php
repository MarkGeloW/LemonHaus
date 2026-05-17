<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->get();

        $products = Product::where('status', 'available')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        return view('admin.orders', compact('orders', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'product_id' => 'required|array',
            'product_id.*' => 'required|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1',
        ]);

        try {
            DB::transaction(function () use ($request) {
                foreach ($request->product_id as $index => $productId) {
                    $product = Product::lockForUpdate()->findOrFail($productId);
                    $quantity = (int) $request->quantity[$index];

                    if ($product->stock < $quantity) {
                        throw new \Exception($product->name . ' does not have enough product stock.');
                    }

                    Order::create([
                        'customer_name' => $request->customer_name,
                        'product_name' => $product->name,
                        'price' => $product->price,
                        'quantity' => $quantity,
                        'status' => 'pending',
                    ]);

                    $product->decrement('stock', $quantity);
                }
            });

            return redirect()
                ->route('admin.orders.index')
                ->with('success', 'Order created successfully.');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['stock' => $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,in_progress,ready,completed',
        ]);

        $order = Order::findOrFail($id);

        $order->update([
            'status' => $request->status,
        ]);

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order status updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()
            ->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}