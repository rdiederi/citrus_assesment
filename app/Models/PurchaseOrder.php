<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'type',
        'status',
        'created_by',
        'distributor_id',
        'supplier_id',
        'parent_po_id',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function distributor()
    {
        return $this->belongsTo(Distributor::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function parentPO()
    {
        return $this->belongsTo(PurchaseOrder::class, 'parent_po_id');
    }

    public function childPOs()
    {
        return $this->hasMany(PurchaseOrder::class, 'parent_po_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    // Generate a unique PO number
    public static function generatePONumber($type)
    {
        $prefix = $type; // Either 'POD' or 'POS'
        $latestPO = self::where('type', $type)->latest()->first();

        if ($latestPO) {
            // Extract the numeric part and increment
            $latestNumber = (int) substr($latestPO->po_number, 4);
            $newNumber = $latestNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . '-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
