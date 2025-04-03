<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class PurchaseOrderItemController extends Controller
{
    /**
     * Return a list of items for the given purchase order
     *
     * @param PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PurchaseOrder $purchaseOrder)
    {
        $items = $purchaseOrder->items()->with('product')->get();
        return response()->json($items);
    }

    /**
     * Store a newly created purchase order item in storage.
     *
     * Validates the request data, calculates the total price, and creates a new purchase order item.
     * The created item is returned with its associated product.
     *
     * @param Request $request The incoming request containing the item data.
     * @param PurchaseOrder $purchaseOrder The purchase order to which the item belongs.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the newly created item and its product.
     */

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

    /**
     * Display the specified purchase order item.
     *
     * Retrieves the specified item with its associated product.
     * The item is returned as JSON.
     *
     * @param PurchaseOrderItem $item The item to be retrieved.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the item and its product.
     */
    public function show(PurchaseOrderItem $item)
    {
        return response()->json($item->load('product'));
    }

    /**
     * Update the specified purchase order item in storage.
     *
     * Validates the request data, recalculates the total price if quantity or unit price were changed,
     * and updates the specified item with the given data.
     * The updated item is returned with its associated product.
     *
     * @param Request $request The incoming request containing the item data.
     * @param PurchaseOrderItem $item The item to be updated.
     * @return \Illuminate\Http\JsonResponse A JSON response containing the updated item and its product.
     */
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

    /**
     * Remove the specified purchase order item from storage.
     *
     * @param PurchaseOrderItem $item The item to be deleted.
     * @return \Illuminate\Http\JsonResponse A JSON response with a 204 status.
     */
    public function destroy(PurchaseOrderItem $item)
    {
        $item->delete();
        return response()->json(null, 204);
    }
}
