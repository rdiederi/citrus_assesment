<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\PurchaseOrder;

class PurchaseOrdersTableSeeder extends Seeder
{
    public function run()
    {
        // Make sure these references match real IDs in your DB:
        //  - user with id=1
        //  - distributor with id=1
        //  - supplier with id=2
        //  - if you have parent PO relationships, you can set parent_po_id => some purchase order's id
        //  - The 'type' must be either 'POD' or 'POS'
        //  - 'status' must be one of: ['New', 'Accepted', 'In Delivery', 'Delivered', 'Rejected', 'Cancelled']

        $purchaseOrders = [
            // 1) A "POD" type (purchase order from distributor)
            [
                'po_number'       => 'POD-' . strtoupper(Str::random(6)),
                'order_number'    => 'POD-' . strtoupper(Str::random(6)),
                'type'            => 'POD',
                'status'          => 'New',
                'distributor_id'  => 1,
                'supplier_id'     => null,
                'created_by'      => 1,
                'parent_po_id'    => null, // or some existing PO's id if needed
            ],
            // 2) Another "POD" type, maybe different status
            [
                'po_number'       => 'POD-' . strtoupper(Str::random(6)),
                'order_number'    => 'POD-' . strtoupper(Str::random(6)),
                'type'            => 'POD',
                'status'          => 'Cancelled',
                'distributor_id'  => 1,
                'supplier_id'     => null,
                'created_by'      => 1,
                'parent_po_id'    => null,
            ],
            // 3) A "POS" type (purchase order to a supplier)
            [
                'po_number'       => 'POS-' . strtoupper(Str::random(6)),
                'order_number'    => 'POS-' . strtoupper(Str::random(6)),
                'type'            => 'POS',
                'status'          => 'In Delivery',
                'distributor_id'  => null,
                'supplier_id'     => 2,
                'created_by'      => 1,
                'parent_po_id'    => null,
            ],
            // 4) Another "POS" type, maybe Rejected
            [
                'po_number'       => 'POS-' . strtoupper(Str::random(6)),
                'order_number'    => 'POS-' . strtoupper(Str::random(6)),
                'type'            => 'POS',
                'status'          => 'Rejected',
                'distributor_id'  => null,
                'supplier_id'     => 2,
                'created_by'      => 1,
                'parent_po_id'    => null,
            ],
        ];

        foreach ($purchaseOrders as $data) {
            PurchaseOrder::create($data);
        }
    }
}
