<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});


Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard')->middleware(['auth:sanctum', 'verified']);

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
// config('jetstream.auth_session'),
Route::middleware([
    'auth:sanctum',
    'verified',
])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Suppliers
    Route::get('/suppliers', function () {
        return Inertia::render('Suppliers/Index');
    })->name('suppliers');

    Route::get('/suppliers/create', function () {
        return Inertia::render('Suppliers/Create');
    })->name('suppliers.create');

    Route::get('/suppliers/{supplier}', function ($id) {
        return Inertia::render('Suppliers/Show', ['id' => $id]);
    })->name('suppliers.show');

    // Distributors
    Route::get('/distributors', function () {
        return Inertia::render('Distributors/Index');
    })->name('distributors');

    // Products
    Route::get('/products', function () {
        return Inertia::render('Products/Index');
    })->name('products');

    // Purchase Orders
    Route::get('/purchase-orders', function () {
        return Inertia::render('PurchaseOrders/Index');
    })->name('purchase-orders');

    Route::get('/purchase-orders/create', function () {
        return Inertia::render('PurchaseOrders/Create');
    })->name('purchase-orders.create');

    Route::get('/purchase-orders/{purchaseOrder}', function ($id) {
        return Inertia::render('PurchaseOrders/Show', ['id' => $id]);
    })->name('purchase-orders.show');
});

require __DIR__.'/auth.php';
