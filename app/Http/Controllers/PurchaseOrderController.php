<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of purchase orders with related distributor, supplier, and product details.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function index()
    {
        $purchaseOrders = PurchaseOrder::with(['distributor', 'supplier', 'items.product'])->get();
        return response()->json($purchaseOrders);
    }

    /**
     * Store a newly created purchase order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|in:POD,POS',
            'distributor_id' => 'required_if:type,POD|exists:distributors,id|nullable',
            'supplier_id' => 'required_if:type,POS|exists:suppliers,id|nullable',
            'parent_po_id' => 'nullable|exists:purchase_orders,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.delivery_date' => 'required|date',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            // Generate PO number
            $poNumber = PurchaseOrder::generatePONumber($validated['type']);

            // Create purchase order
            $purchaseOrder = PurchaseOrder::create([
                'po_number' => $poNumber,
                'type' => $validated['type'],
                'status' => 'New',
                'created_by' => Auth::user()->id,
                'distributor_id' => $validated['distributor_id'] ?? null,
                'supplier_id' => $validated['supplier_id'] ?? null,
                'parent_po_id' => $validated['parent_po_id'] ?? null,
            ]);

            // Create PO items
            foreach ($validated['items'] as $item) {
                $totalPrice = $item['unit_price'] * $item['quantity'];

                PurchaseOrderItem::create([
                    'purchase_order_id' => $purchaseOrder->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'delivery_date' => $item['delivery_date'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $totalPrice,
                ]);
            }

            DB::commit();

            return response()->json(
                PurchaseOrder::with(['distributor', 'supplier', 'items.product'])->find($purchaseOrder->id),
                201
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to create purchase order', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified purchase order.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['distributor', 'supplier', 'items.product', 'parentPO', 'childPOs']);
        return response()->json($purchaseOrder);
    }

    /**
     * Update the specified purchase order in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:New,Accepted,In Delivery,Delivered,Rejected,Cancelled',
            'distributor_id' => 'sometimes|exists:distributors,id|nullable',
            'supplier_id' => 'sometimes|exists:suppliers,id|nullable',
        ]);

        $purchaseOrder->update($validated);
        return response()->json($purchaseOrder);
    }

    /**
     * Remove the specified purchase order from storage.
     *
     * @param  \App\Models\PurchaseOrder  $purchaseOrder
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseOrder $purchaseOrder)
    {
        // Delete associated items first
        $purchaseOrder->items()->delete();
        $purchaseOrder->delete();
        return response()->json(null, 204);
    }

    /**
     * Check and process the supplier approval for a purchase order.
     *
     * This method handles the approval of a supplier for a given purchase order of type 'POD'
     * and updates the order status accordingly. If approved, a linked purchase order of type 'POS'
     * is created with the relevant items. If not approved, the purchase order is marked as rejected.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\PurchaseOrder $purchaseOrder
     * @return \Illuminate\Http\JsonResponse
     *
     * Validates the request data to determine if the purchase order is approved and requires a supplier ID.
     * If approved, a new 'POS' order is created with items from the current purchase order.
     * On failure, the transaction is rolled back and an error message is returned.
     */

    public function checkSupplier(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->type !== 'POD' || $purchaseOrder->status !== 'New') {
            return response()->json(['message' => 'Invalid purchase order state'], 400);
        }

        $validated = $request->validate([
            'approved' => 'required|boolean',
            'supplier_id' => 'required_if:approved,true|exists:suppliers,id',
        ]);

        try {
            DB::beginTransaction();

            if ($validated['approved']) {
                // Update status
                $purchaseOrder->status = 'Accepted';
                $purchaseOrder->save();

                // Create linked POS
                $posItems = [];
                foreach ($purchaseOrder->items as $item) {
                    $posItems[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->quantity,
                        'delivery_date' => $item->delivery_date,
                        'unit_price' => $item->unit_price,
                    ];
                }

                $posOrder = PurchaseOrder::create([
                    'po_number' => PurchaseOrder::generatePONumber('POS'),
                    'type' => 'POS',
                    'status' => 'New',
                    'created_by' => Auth::user()->id,
                    'supplier_id' => $validated['supplier_id'],
                    'parent_po_id' => $purchaseOrder->id,
                ]);

                // Create POS items
                foreach ($posItems as $item) {
                    $totalPrice = $item['unit_price'] * $item['quantity'];

                    PurchaseOrderItem::create([
                        'purchase_order_id' => $posOrder->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'delivery_date' => $item['delivery_date'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $totalPrice,
                    ]);
                }

                DB::commit();
                return response()->json([
                    'message' => 'Purchase order accepted and supplier order created',
                    'pod' => $purchaseOrder,
                    'pos' => $posOrder,
                ]);
            } else {
                // Reject the PO
                $purchaseOrder->status = 'Rejected';
                $purchaseOrder->save();

                DB::commit();
                return response()->json([
                    'message' => 'Purchase order rejected',
                    'purchase_order' => $purchaseOrder,
                ]);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to process purchase order', 'error' => $e->getMessage()], 500);
        }
    }
}
