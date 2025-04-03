<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Supplier;
use App\Models\Distributor;
use App\Models\Product;
use App\Models\PurchaseOrder;

class DashboardController extends Controller
{
    /**
     * Dashboard index page.
     *
     * Returns an Inertia response with example stats for the dashboard.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        // Example stats
        $supplierCount = Supplier::count();
        $distributorCount = Distributor::count();
        $productCount = Product::count();
        $poCount = PurchaseOrder::count();

        // Return an Inertia response with data
        return Inertia::render('Dashboard', [
            'stats' => [
                'suppliers' => $supplierCount,
                'distributors' => $distributorCount,
                'products' => $productCount,
                'purchaseOrders' => $poCount,
            ],
        ]);
    }
}
