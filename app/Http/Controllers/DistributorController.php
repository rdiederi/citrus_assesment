<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    public function index()
    {
        $distributors = Distributor::all();
        return response()->json($distributors);
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

        $distributor = Distributor::create($validated);
        return response()->json($distributor, 201);
    }

    public function show(Distributor $distributor)
    {
        return response()->json($distributor);
    }

    public function update(Request $request, Distributor $distributor)
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

        $distributor->update($validated);
        return response()->json($distributor);
    }

    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return response()->json(null, 204);
    }
}
