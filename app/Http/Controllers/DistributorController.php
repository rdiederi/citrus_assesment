<?php

namespace App\Http\Controllers;

use App\Models\Distributor;
use Illuminate\Http\Request;

class DistributorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $distributors = Distributor::all();
        return response()->json($distributors);
    }

    /**
     * Store a newly created distributor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Display the specified distributor.
     *
     * @param  \App\Models\Distributor  $distributor
     * @return \Illuminate\Http\Response
     */
    public function show(Distributor $distributor)
    {
        return response()->json($distributor);
    }

    /**
     * Update the specified distributor in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Distributor  $distributor
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Remove the specified distributor from storage.
     *
     * @param  \App\Models\Distributor  $distributor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Distributor $distributor)
    {
        $distributor->delete();
        return response()->json(null, 204);
    }
}
