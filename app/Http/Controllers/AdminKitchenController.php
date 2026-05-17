<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderRecord;
use Illuminate\Support\Facades\DB;

class AdminKitchenController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')->latest()->get();
        $inProgressOrders = Order::where('status', 'in_progress')->latest()->get();
        $readyOrders = Order::where('status', 'ready')->latest()->get();

        return view('admin.kitchen', compact(
            'pendingOrders',
            'inProgressOrders',
            'readyOrders'
        ));
    }

    public function accept(Order $order)
    {
        $order->update([
            'status' => 'in_progress',
        ]);

        return redirect('/admin/kitchen')
            ->with('success', 'Order accepted.');
    }

    public function ready(Order $order)
    {
        $order->update([
            'status' => 'ready',
        ]);

        return redirect('/admin/kitchen')
            ->with('success', 'Order is ready.');
    }

    public function complete($id)
    {
        DB::transaction(function () use ($id) {
            $order = Order::findOrFail($id);

            if ($order->status !== 'ready') {
                throw new \Exception('Only ready orders can be archived.');
            }

            OrderRecord::create([
                'order_id' => $order->id,
                'customer_name' => $order->customer_name,
                'product_name' => $order->product_name,
                'quantity' => $order->quantity,
                'price' => $order->price,
                'total' => $order->price * $order->quantity,
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            $order->delete();
        });

        return redirect('/admin/kitchen')
            ->with('archive_success', 'Order has been recorded successfully.');
    }
}