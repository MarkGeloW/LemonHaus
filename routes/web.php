<?php

use App\Http\Controllers\AdminStockController;
use App\Http\Controllers\AdminProductController;
use App\Http\Controllers\InventoryController;
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
use App\Http\Controllers\AdminOrderController;
use App\Http\Controllers\AdminKitchenController;
use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CashierDashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('dashboard');
});

Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier', [CashierDashboardController::class, 'index'])
        ->name('cashier.dashboard');
});



Route::middleware(['auth', 'role:admin'])->group(function () {
    // admin routes here

    Route::get('/admin/kitchen', [AdminKitchenController::class, 'index'])
    ->name('admin.kitchen');

Route::patch('/admin/kitchen/orders/{order}/accept', [AdminKitchenController::class, 'accept'])
    ->name('admin.kitchen.orders.accept');

Route::patch('/admin/kitchen/orders/{order}/ready', [AdminKitchenController::class, 'ready'])
    ->name('admin.kitchen.orders.ready');

Route::patch('/admin/kitchen/orders/{id}/complete', [AdminKitchenController::class, 'complete'])
    ->name('admin.kitchen.orders.complete');

    Route::patch('/kitchen/orders/{id}/complete', [KitchenOrderController::class, 'complete'])
        ->name('kitchen.orders.complete');

    Route::get('/kitchen/inventory', function () {
        $items = \App\Models\Inventory::orderBy('name', 'asc')->get();

        return view('kitchen.inventory', compact('items'));
    })->name('kitchen.inventory');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::post('/admin/orders', [AdminOrderController::class, 'store'])
        ->name('admin.orders.store');

    Route::patch('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');

    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy'])
        ->name('admin.orders.destroy');
});


Route::get('/', function () { return redirect()->route('login'); });
/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])->group(function () {

    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.index');

    Route::post('/admin/users', [AdminController::class, 'storeUser'])
        ->name('admin.users.store');

    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])
        ->name('admin.users.destroy');

    Route::get('/admin/orders', [AdminOrderController::class, 'index'])
        ->name('admin.orders.index');

    Route::post('/admin/orders', [AdminOrderController::class, 'store'])
        ->name('admin.orders.store');

    Route::patch('/admin/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');

    Route::delete('/admin/orders/{id}', [AdminOrderController::class, 'destroy'])
        ->name('admin.orders.destroy');

    Route::get('/admin/kitchen', [AdminKitchenController::class, 'index'])
        ->name('admin.kitchen');

    Route::patch('/admin/kitchen/orders/{order}/accept', [AdminKitchenController::class, 'accept'])
        ->name('admin.kitchen.orders.accept');

    Route::patch('/admin/kitchen/orders/{order}/ready', [AdminKitchenController::class, 'ready'])
        ->name('admin.kitchen.orders.ready');

    Route::patch('/admin/kitchen/orders/{id}/complete', [AdminKitchenController::class, 'complete'])
        ->name('admin.kitchen.orders.complete');

    Route::get('/admin/inventory', [InventoryController::class, 'index'])
        ->name('admin.inventory');

    Route::get('/admin/products/create', [AdminProductController::class, 'create'])
        ->name('admin.products.create');

    Route::post('/admin/products/store', [AdminProductController::class, 'store'])
        ->name('admin.products.store');

    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])
        ->name('admin.products.edit');

    Route::put('/admin/products/{product}', [AdminProductController::class, 'update'])
        ->name('admin.products.update');

    Route::delete('/admin/products/{product}', [AdminProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    Route::get('/admin/stocks/create', [AdminStockController::class, 'create'])
        ->name('admin.stocks.create');

    Route::post('/admin/stocks/store', [AdminStockController::class, 'store'])
        ->name('admin.stocks.store');

    Route::get('/admin/stocks/{stock}/edit', [AdminStockController::class, 'edit'])
        ->name('admin.stocks.edit');

    Route::put('/admin/stocks/{stock}', [AdminStockController::class, 'update'])
        ->name('admin.stocks.update');

    Route::delete('/admin/stocks/{stock}', [AdminStockController::class, 'destroy'])
        ->name('admin.stocks.destroy');

    Route::get('/admin/reports', [AdminReportController::class, 'index'])
        ->name('reports.index');

    Route::get('/admin/reports/export', [AdminReportController::class, 'export'])
        ->name('reports.export');
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

 
Route::middleware(['auth', 'role:cashier'])->group(function () {
    Route::get('/cashier', [AdminDashboardController::class, 'index'])
        ->name('cashier.dashboard');

    Route::get('/cashier/orders', [CashierOrderController::class, 'index'])
        ->name('cashier.orders.index');

    Route::post('/cashier/orders', [CashierOrderController::class, 'store'])
        ->name('cashier.orders.store');

    Route::delete('/cashier/orders/{id}', [CashierOrderController::class, 'destroy'])
        ->name('cashier.orders.destroy');

    Route::get('/cashier/kitchen', [CashierOrderController::class, 'kitchen'])
        ->name('cashier.kitchen');
});

    Route::get('/cashier/kitchen', function () {
        $pendingOrders = \App\Models\Order::where('status', 'pending')->get();
        $inProgressOrders = \App\Models\Order::where('status', 'in_progress')->get();
        $readyOrders = \App\Models\Order::where('status', 'ready')->get();
        return view('cashier.kitchen', compact('pendingOrders', 'inProgressOrders', 'readyOrders'));
    })->name('cashier.kitchen');
});



/*
|--------------------------------------------------------------------------
| KITCHEN ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kitchen'])->group(function () {

    Route::get('/kitchen', [AdminDashboardController::class, 'index'])
        ->name('kitchen.dashboard');

    Route::get('/kitchen/kitchen', [KitchenOrderController::class, 'index'])
        ->name('kitchen.index');

    Route::patch('/kitchen/orders/{order}/accept', [KitchenOrderController::class, 'accept'])
        ->name('kitchen.orders.accept');

    Route::patch('/kitchen/orders/{order}/ready', [KitchenOrderController::class, 'ready'])
        ->name('kitchen.orders.ready');

    Route::patch('/kitchen/orders/{id}/complete', [KitchenOrderController::class, 'complete'])
        ->name('kitchen.orders.complete');

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
'expiration_date' => 'nullable|date|after_or_equal:date_added',        ]);

        $item = \App\Models\Inventory::create($validated);

        if (class_exists(\App\Models\AuditLog::class)) {
            \App\Models\AuditLog::create([
                'user_id' => auth()->id(),
                'user_name' => auth()->user()->name,
                'action' => 'Added Inventory',
                'details' => "Kitchen Staff added {$item->stock_level} {$item->unit} of {$item->name}",
            ]);
        }

        return redirect()
            ->route('kitchen.inventory')
            ->with('success', 'New stock added to inventory successfully.');
    })->name('kitchen.inventory.store');
});



Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/kitchen/kitchen', [KitchenOrderController::class, 'index'])
        ->name('kitchen.index');

    Route::patch('/kitchen/orders/{id}/complete', [KitchenOrderController::class, 'complete'])
        ->name('kitchen.orders.complete');
});
// ==========================================
// INVENTORY ROUTES
// ==========================================
Route::middleware(['auth', 'role:inventory'])->group(function () {
    Route::get('/inventory', function () { return view('inventory.index'); })->name('inventory.dashboard');
    Route::get('/inventory/inventory', function () { return view('inventory.inventory'); })->name('inventory.inventory');
});



Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/inventory', [InventoryController::class, 'index'])
        ->name('admin.inventory');

    Route::get('/admin/products/create', [AdminProductController::class, 'create'])
        ->name('admin.products.create');

    Route::post('/admin/products/store', [AdminProductController::class, 'store'])
        ->name('admin.products.store');
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/inventory', [InventoryController::class, 'index'])
        ->name('admin.inventory');

    Route::get('/admin/products/create', [AdminProductController::class, 'create'])
        ->name('admin.products.create');

    Route::post('/admin/products/store', [AdminProductController::class, 'store'])
        ->name('admin.products.store');

    Route::get('/admin/products/{product}/edit', [AdminProductController::class, 'edit'])
        ->name('admin.products.edit');

    Route::put('/admin/products/{product}', [AdminProductController::class, 'update'])
        ->name('admin.products.update');

    Route::delete('/admin/products/{product}', [AdminProductController::class, 'destroy'])
        ->name('admin.products.destroy');

    Route::get('/admin/stocks/create', [AdminStockController::class, 'create'])
        ->name('admin.stocks.create');

    Route::post('/admin/stocks/store', [AdminStockController::class, 'store'])
        ->name('admin.stocks.store');

    Route::get('/admin/stocks/{stock}/edit', [AdminStockController::class, 'edit'])
        ->name('admin.stocks.edit');

    Route::put('/admin/stocks/{stock}', [AdminStockController::class, 'update'])
        ->name('admin.stocks.update');

    Route::delete('/admin/stocks/{stock}', [AdminStockController::class, 'destroy'])
        ->name('admin.stocks.destroy');
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