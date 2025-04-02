<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price_2023',
        'price_2024',
        'price_2025',
    ];

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function getCurrentPrice()
    {
        $year = date('Y');
        $priceColumn = 'price_' . $year;

        if (property_exists($this, $priceColumn)) {
            return $this->$priceColumn;
        }

        // Return the most recent price if current year's price is not available
        return $this->price_2025 ?? $this->price_2024 ?? $this->price_2023 ?? 0;
    }
}
