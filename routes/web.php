<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CashierOrderController;
use App\Http\Controllers\KitchenOrderController;

// Public route
Route::get('/', function () {
    return redirect()->route('login');
});

// Admin only
// Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // Inventory Management
    Route::get('/admin/inventory', [AdminController::class, 'inventoryIndex'])->name('inventory.index');
    Route::get('/admin/inventory/create', [AdminController::class, 'inventoryCreate'])->name('inventory.create');
    Route::post('/admin/inventory', [AdminController::class, 'storeInventory'])->name('inventory.store');

    // Other Admin Routes
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
    
    Route::get('/admin/orders', function () { return view('admin.orders'); })->name('orders.index');
    Route::get('/admin/kitchen', function () { return view('admin.kitchen'); })->name('kitchen.index');
    Route::get('/admin/reports', function () { return view('admin.reports'); })->name('reports.index');
});




//cashier
Route::middleware(['auth','role:cashier'])->group(function () {
    Route::get('/cashier', function () {
        return view('cashier.index');
    })->name('cashier.dashboard');

    Route::get('/cashier/orders', function () {
        return view('cashier.orders');
    })->name('cashier.orders');

    Route::get('/cashier/kitchen', function () {
        return view('cashier.kitchen');
    })->name('cashier.kitchen');
});


Route::middleware(['auth', 'role:cashier'])->group(function () {
    // This is the route for viewing orders (index route)
    Route::get('/cashier/orders', [CashierOrderController::class, 'index'])
        ->name('cashier.orders');

    // This is the route for creating a new order
    Route::post('/cashier/orders', [CashierOrderController::class, 'store'])
        ->name('cashier.orders.store');

    // Delete route for an order
    Route::delete('/cashier/orders/{order}', [CashierOrderController::class, 'destroy'])
        ->name('cashier.orders.destroy');
});


//kitchen
Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/kitchen', function () {
        return view('kitchen.index');
    })->name('kitchen.dashboard');

    Route::get('/kitchen/kitchen', function () {
        return view('kitchen.kitchen');
    })->name('kitchen.kitchen');

   

});

//kitchen to cashier view
Route::middleware(['auth', 'role:cashier'])->get('/cashier/kitchen', function () {
    // Fetch actual orders instead of just the count
    $pendingOrders = \App\Models\Order::where('status', 'pending')->get();
    $inProgressOrders = \App\Models\Order::where('status', 'in_progress')->get();
    $readyOrders = \App\Models\Order::where('status', 'ready')->get();

    // Pass the data to the view
    return view('cashier.kitchen', compact('pendingOrders', 'inProgressOrders', 'readyOrders'));
})->name('cashier.kitchen');


Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/kitchen/kitchen', [KitchenOrderController::class, 'index'])
        ->name('kitchen.kitchen');

    Route::patch('/kitchen/orders/{order}/accept', [KitchenOrderController::class, 'accept'])
        ->name('kitchen.orders.accept');

    Route::patch('/kitchen/orders/{order}/ready', [KitchenOrderController::class, 'ready'])
        ->name('kitchen.orders.ready');
        
});

//inventory
Route::middleware(['auth', 'role:inventory'])->group(function () {
    Route::get('/inventory', function () {
        return view('inventory.index');
    })->name('inventory.dashboard');

    Route::get('/inventory/inventory', function () {
        return view('inventory.inventory');
    })->name('inventory.inventory');

    
});






// Authenticated users
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';