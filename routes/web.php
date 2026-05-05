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

// Public route
Route::get('/', function () {
    return redirect()->route('login');
});

// ==========================================
// ADMIN ROUTES
// ==========================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        $activeOrdersCount = \App\Models\Order::whereIn('status', ['pending', 'in_progress'])->count();
        $todaysSales = \App\Models\Order::whereDate('created_at', today())->sum('total');
        $lowStockCount = \App\Models\Inventory::whereColumn('stock_level', '<=', 'min_stock')->count();

        $weeklySalesData = collect(range(6, 0))->map(function ($daysAgo) {
            $date = today()->subDays($daysAgo);
            return [
                'day' => $date->format('D'),
                'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')
            ];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;

        return view('admin.dashboard', compact('activeOrdersCount', 'todaysSales', 'lowStockCount', 'weeklySalesData', 'maxSales'));
    })->name('dashboard');

    // Inventory
    Route::get('/admin/inventory', [AdminController::class, 'inventoryIndex'])->name('inventory.index');
    Route::get('/admin/inventory/create', [AdminController::class, 'inventoryCreate'])->name('inventory.create');
    Route::post('/admin/inventory', [AdminController::class, 'storeInventory'])->name('inventory.store');

    // Users & Audit
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    
    // Orders
    Route::get('/admin/orders', function () { return view('admin.orders'); })->name('orders.index');
    
    // Kitchen Display (Live/Read-Only)
    Route::get('/admin/kitchen', function () { 
        $pendingOrders = \App\Models\Order::where('status', 'pending')->latest()->get();
        $inProgressOrders = \App\Models\Order::where('status', 'in_progress')->latest()->get();
        $readyOrders = \App\Models\Order::where('status', 'ready')->latest()->get();
        return view('admin.kitchen', compact('pendingOrders', 'inProgressOrders', 'readyOrders')); 
    })->name('kitchen.index');
    
    // Reports & Analytics (Live with Date Filter)
    Route::get('/admin/reports', function (Request $request) { 
        $days = (int) $request->query('days', 7);
        $startDate = today()->subDays($days - 1);

        $totalRevenue = \App\Models\Order::whereDate('created_at', '>=', $startDate)->sum('total');
        $totalOrders = \App\Models\Order::whereDate('created_at', '>=', $startDate)->count();
        $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $weeklySalesData = collect(range($days - 1, 0))->map(function ($daysAgo) use ($days) {
            $date = today()->subDays($daysAgo);
            return [
                'day' => $date->format($days > 7 ? 'M d' : 'D'),
                'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')
            ];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;

        $topItems = \App\Models\Order::whereDate('created_at', '>=', $startDate)
            ->selectRaw('product_name, sum(quantity) as total_quantity')
            ->groupBy('product_name')
            ->orderByDesc('total_quantity')
            ->limit(3)
            ->get();

        return view('admin.reports', compact('totalRevenue', 'totalOrders', 'avgOrderValue', 'weeklySalesData', 'maxSales', 'topItems', 'days')); 
    })->name('reports.index');

    // Export CSV
    Route::get('/admin/reports/export', function (Request $request) {
        $days = (int) $request->query('days', 7);
        $startDate = today()->subDays($days - 1);
        
        $orders = \App\Models\Order::whereDate('created_at', '>=', $startDate)->orderBy('created_at', 'desc')->get();
        
        $csvData = "Order ID,Customer Name,Product,Quantity,Total Price,Status,Date Ordered\n";
        foreach($orders as $order) {
            $csvData .= "{$order->id},{$order->customer_name},{$order->product_name},{$order->quantity},{$order->total},{$order->status},{$order->created_at->format('Y-m-d H:i:s')}\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="lemonhaus_sales_last_'.$days.'_days.csv"');
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
            return [
                'day' => $date->format('D'),
                'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')
            ];
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
            return [
                'day' => $date->format('D'),
                'total' => \App\Models\Order::whereDate('created_at', $date)->sum('total')
            ];
        });
        $maxSales = $weeklySalesData->max('total') ?: 100;

        return view('kitchen.index', compact('activeOrdersCount', 'todaysSales', 'lowStockCount', 'weeklySalesData', 'maxSales'));
    })->name('kitchen.dashboard');

    Route::get('/kitchen/kitchen', [KitchenOrderController::class, 'index'])->name('kitchen.kitchen');
    Route::patch('/kitchen/orders/{order}/accept', [KitchenOrderController::class, 'accept'])->name('kitchen.orders.accept');
    Route::patch('/kitchen/orders/{order}/ready', [KitchenOrderController::class, 'ready'])->name('kitchen.orders.ready');
});

// ==========================================
// INVENTORY ROUTES
// ==========================================
Route::middleware(['auth', 'role:inventory'])->group(function () {
    Route::get('/inventory', function () { return view('inventory.index'); })->name('inventory.dashboard');
    Route::get('/inventory/inventory', function () { return view('inventory.inventory'); })->name('inventory.inventory');
});

// ==========================================
// AUTH & PROFILE ROUTES
// ==========================================
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);
    Route::put('password', [PasswordController::class, 'update'])->name('password.update');
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});