<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierOrderController;
use App\Http\Controllers\KitchenOrderController;
use Illuminate\Http\Request;

Route::get('/', function () { return redirect()->route('login'); });

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        $activeOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
        $todaysSales = \App\Models\Order::whereDate('created_at', today())->sum('total');
        $lowStockCount = \App\Models\Inventory::whereColumn('stock_level', '<=', 'min_stock')->count();
        $weeklySalesData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            return ['day' => $date->format('D'), 'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;
        return view('admin.dashboard', compact('activeOrdersCount', 'todaysSales', 'lowStockCount', 'weeklySalesData', 'maxSales'));
    })->name('dashboard');

    Route::get('/admin/inventory', [AdminController::class, 'inventoryIndex'])->name('inventory.index');
    Route::get('/admin/inventory/create', [AdminController::class, 'inventoryCreate'])->name('inventory.create');
    Route::post('/admin/inventory', [AdminController::class, 'storeInventory'])->name('inventory.store');
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    Route::get('/admin/orders', function () { return view('admin.orders'); })->name('orders.index');
    
    Route::get('/admin/kitchen', function () { 
        $pendingOrders = \App\Models\Order::where('status', 'pending')->latest()->get();
        $inProgressOrders = \App\Models\Order::where('status', 'in_progress')->latest()->get();
        $readyOrders = \App\Models\Order::where('status', 'ready')->latest()->get();
        return view('admin.kitchen', compact('pendingOrders', 'inProgressOrders', 'readyOrders')); 
    })->name('kitchen.index');
    
    Route::get('/admin/reports', function (Request $request) { 
        $days = (int) $request->query('days', 7);
        $startDate = today()->subDays($days - 1);
        $totalRevenue = \App\Models\Order::whereDate('created_at', '>=', $startDate)->sum('total');
        $totalOrders = \App\Models\Order::whereDate('created_at', '>=', $startDate)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;
        $weeklySalesData = collect(range($days - 1, 0))->map(function ($daysAgo) use ($days) {
            $date = today()->subDays($daysAgo);
            return ['day' => $date->format($days > 7 ? 'M d' : 'D'), 'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;
        $topItems = \App\Models\Order::whereDate('created_at', '>=', $startDate)->selectRaw('product_name, sum(quantity) as total_quantity')->groupBy('product_name')->orderByDesc('total_quantity')->limit(3)->get();
        return view('admin.reports', compact('totalRevenue', 'totalOrders', 'avgOrderValue', 'weeklySalesData', 'maxSales', 'topItems', 'days')); 
    })->name('reports.index');

    Route::get('/admin/reports/export', function (Request $request) {
        $days = (int) $request->query('days', 7);
        $startDate = today()->subDays($days - 1);
        $orders = \App\Models\Order::whereDate('created_at', '>=', $startDate)->orderBy('created_at', 'desc')->get();
        $csvData = "Order ID,Customer Name,Product,Quantity,Total Price,Status,Date Ordered\n";
        foreach($orders as $order) { $csvData .= "{$order->id},{$order->customer_name},{$order->product_name},{$order->quantity},{$order->total},{$order->status},{$order->created_at->format('Y-m-d H:i:s')}\n"; }
        return response($csvData)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="lemonhaus_sales_last_'.$days.'_days.csv"');
    })->name('reports.export');
});

// ==========================================
// CASHIER ROUTES
// ==========================================
Route::middleware(['auth','role:cashier'])->group(function () {
    Route::get('/cashier', function () {
        $activeOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
        $todaysSales = \App\Models\Order::whereDate('created_at', today())->sum('total');
        $lowStockCount = \App\Models\Inventory::whereColumn('stock_level', '<=', 'min_stock')->count();
        $weeklySalesData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            return ['day' => $date->format('D'), 'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;
        return view('cashier.index', compact('activeOrdersCount', 'todaysSales', 'lowStockCount', 'weeklySalesData', 'maxSales'));
    })->name('cashier.dashboard');

    Route::get('/cashier/orders', [CashierOrderController::class, 'index'])->name('cashier.orders');
    Route::post('/cashier/orders', [CashierOrderController::class, 'store'])->name('cashier.orders.store');
    Route::delete('/cashier/orders/{order}', [CashierOrderController::class, 'destroy'])->name('cashier.orders.destroy');

    Route::get('/cashier/kitchen', function () {
        $pendingOrders = \App\Models\Order::where('status', 'pending')->get();
        $inProgressOrders = \App\Models\Order::where('status', 'in_progress')->get();
        $readyOrders = \App\Models\Order::where('status', 'ready')->get();
        return view('cashier.kitchen', compact('pendingOrders', 'inProgressOrders', 'readyOrders'));
    })->name('cashier.kitchen');
});

// ==========================================
// KITCHEN ROUTES
// ==========================================
Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/kitchen', function () {
        $activeOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
        $todaysSales = \App\Models\Order::whereDate('created_at', today())->sum('total');
        $lowStockCount = \App\Models\Inventory::whereColumn('stock_level', '<=', 'min_stock')->count();
        $weeklySalesData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            return ['day' => $date->format('D'), 'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;
        return view('kitchen.index', compact('activeOrdersCount', 'todaysSales', 'lowStockCount', 'weeklySalesData', 'maxSales'));
    })->name('kitchen.dashboard');

    Route::get('/kitchen/kitchen', [KitchenOrderController::class, 'index'])->name('kitchen.kitchen');
    Route::patch('/kitchen/orders/{order}/accept', [KitchenOrderController::class, 'accept'])->name('kitchen.orders.accept');
    Route::patch('/kitchen/orders/{order}/ready', [KitchenOrderController::class, 'ready'])->name('kitchen.orders.ready');
    
    // KITCHEN INVENTORY CAPABILITIES
    Route::get('/kitchen/inventory', function () {
        $items = \App\Models\Inventory::orderBy('name', 'asc')->get();
        return view('kitchen.inventory', compact('items'));
    })->name('kitchen.inventory');

    Route::get('/kitchen/inventory/create', function () {
        return view('kitchen.inventory_create');
    })->name('kitchen.inventory.create');

    Route::post('/kitchen/inventory', function (Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string',
            'stock_level' => 'required|integer|min:0',
            'unit' => 'required|string',
            'min_stock' => 'required|integer|min:0',
            'date_added' => 'required|date',
            'expiration_date' => 'required|date|after_or_equal:date_added',
        ]);
        
        $item = \App\Models\Inventory::create($validated);
        
        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create(['user_id' => auth()->id(), 'user_name' => auth()->user()->name, 'action' => 'Added Inventory', 'details' => "Kitchen Staff added {$item->stock_level} {$item->unit} of {$item->name}"]);
        }
        
        return redirect()->route('kitchen.inventory')->with('success', 'New stock added to inventory successfully.');
    })->name('kitchen.inventory.store');
});

// ==========================================
// INVENTORY ROUTES
// ==========================================
Route::middleware(['auth', 'role:inventory'])->group(function () {
    Route::get('/inventory', function () { return view('inventory.index'); })->name('inventory.dashboard');
    Route::get('/inventory/inventory', function () { return view('inventory.inventory'); })->name('inventory.inventory');
});

// ==========================================
// AUTH ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});