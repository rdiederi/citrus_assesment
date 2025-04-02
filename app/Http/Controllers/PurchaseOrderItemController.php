<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    public function index(PurchaseOrder $purchaseOrder)
    {
        $items = $purchaseOrder->items()->with('product')->get();
        return response()->json($items);
    }

    public function store(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'delivery_date' => 'required|date',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $totalPrice = $validated['unit_price'] * $validated['quantity'];

        $item = PurchaseOrderItem::create([
            'purchase_order_id' => $purchaseOrder->id,
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'delivery_date' => $validated['delivery_date'],
            'unit_price' => $validated['unit_price'],
            'total_price' => $totalPrice,
        ]);

        return response()->json($item->load('product'), 201);
    }

    public function show(PurchaseOrderItem $item)
    {
        return response()->json($item->load('product'));
    }

    public function update(Request $request, PurchaseOrderItem $item)
    {
        $validated = $request->validate([
            'product_id' => 'sometimes|required|exists:products,id',
            'quantity' => 'sometimes|required|integer|min:1',
            'delivery_date' => 'sometimes|required|date',
            'unit_price' => 'sometimes|required|numeric|min:0',
        ]);

        if (isset($validated['quantity']) || isset($validated['unit_price'])) {
            $quantity = $validated['quantity'] ?? $item->quantity;
            $unitPrice = $validated['unit_price'] ?? $item->unit_price;
            $validated['total_price'] = $quantity * $unitPrice;
        }

        $item->update($validated);
        return response()->json($item->load('product'));
    }

    public function destroy(PurchaseOrderItem $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
