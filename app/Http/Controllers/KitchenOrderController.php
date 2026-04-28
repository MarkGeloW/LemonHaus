<?php

namespace App\Http\Controllers;

use App\Models\Order;

class KitchenOrderController extends Controller
{
    public function index()
    {
        $pendingOrders = Order::where('status', 'pending')->latest()->get();

        $inProgressOrders = Order::where('status', 'in_progress')->latest()->get();

        $readyOrders = Order::where('status', 'ready')->latest()->get();

        return view('kitchen.kitchen', compact(
            'pendingOrders',
            'inProgressOrders',
            'readyOrders'
        ));
    }

    public function accept(Order $order)
    {
        $order->update([
            'status' => 'in_progress'
        ]);

        return redirect()->back()->with('success', 'Order accepted.');
    }

    public function ready(Order $order)
    {
        $order->update([
            'status' => 'ready'
        ]);

        return redirect()->back()->with('success', 'Order is ready.');
    }
}