<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'address',
        'country',
        'vat_number',
        'sales_contact_name',
        'sales_contact_email',
        'sales_contact_phone',
        'logistics_contact_name',
        'logistics_contact_email',
        'logistics_contact_phone',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
