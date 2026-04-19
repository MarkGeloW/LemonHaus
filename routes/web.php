<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public route
Route::get('/', function () {
    return redirect()->route('login');
});

// Admin only
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.users.store');
    Route::delete('/admin/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');

    Route::get('/admin/orders', function () {
        return view('admin.orders');
    })->name('orders.index');

    Route::get('/admin/kitchen', function () {
        return view('admin.kitchen');
    })->name('kitchen.index');

    Route::get('/admin/inventory', function () {
        return view('admin.inventory');
    })->name('inventory.index');

    Route::get('/admin/reports', function () {
        return view('admin.reports');
    })->name('reports.index');
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


//kitchen
Route::middleware(['auth', 'role:kitchen'])->group(function () {
    Route::get('/kitchen', function () {
        return view('kitchen.index');
    })->name('kitchen.dashboard');

    Route::get('/kitchen/kitchen', function () {
        return view('kitchen.kitchen');
    })->name('kitchen.kitchen');

    Route::get('/kitchen/inventory', function () {
        return view('kitchen.inventory');
    })->name('kitchen.inventory');

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