<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::all();
        return response()->json($suppliers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'address' => 'required|string',
            'country' => 'required|string|max:255',
            'vat_number' => 'required|string|max:255',
            'sales_contact_name' => 'required|string|max:255',
            'sales_contact_email' => 'required|email|max:255',
            'sales_contact_phone' => 'required|string|max:255',
            'logistics_contact_name' => 'required|string|max:255',
            'logistics_contact_email' => 'required|email|max:255',
            'logistics_contact_phone' => 'required|string|max:255',
        ]);

        $supplier = Supplier::create($validated);
        $suppliers = Supplier::all();
        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers,
            'success' => session('success'),
        ]);
    }

    public function show(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'business_name' => 'sometimes|required|string|max:255',
            'address' => 'sometimes|required|string',
            'country' => 'sometimes|required|string|max:255',
            'vat_number' => 'sometimes|required|string|max:255',
            'sales_contact_name' => 'sometimes|required|string|max:255',
            'sales_contact_email' => 'sometimes|required|email|max:255',
            'sales_contact_phone' => 'sometimes|required|string|max:255',
            'logistics_contact_name' => 'sometimes|required|string|max:255',
            'logistics_contact_email' => 'sometimes|required|email|max:255',
            'logistics_contact_phone' => 'sometimes|required|string|max:255',
        ]);

        $supplier->update($validated);
        return response()->json($supplier);
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(null, 204);
    }
}
