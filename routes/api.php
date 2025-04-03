<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseOrderItemController;
use App\Http\Controllers\AuthController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);

// User routes
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Role routes
Route::apiResource('roles', RoleController::class)->middleware('auth:sanctum');

// Supplier routes
Route::apiResource('suppliers', SupplierController::class)->middleware('auth:sanctum');

// Distributor routes
Route::apiResource('distributors', DistributorController::class)->middleware('auth:sanctum');

// Product routes
Route::apiResource('products', ProductController::class)->middleware('auth:sanctum');

// Purchase Order routes
Route::apiResource('purchase-orders', PurchaseOrderController::class)->middleware('auth:sanctum');
Route::post('purchase-orders/{purchaseOrder}/check-supplier', [PurchaseOrderController::class, 'checkSupplier'])
    ->middleware('auth:sanctum');

// Purchase Order Item routes
Route::apiResource('purchase-orders.items', PurchaseOrderItemController::class)
    ->shallow()
    ->middleware('auth:sanctum');
