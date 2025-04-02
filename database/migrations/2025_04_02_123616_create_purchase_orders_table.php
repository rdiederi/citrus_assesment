<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('po_number')->unique(); // POD-xxxx or POS-xxxx
            $table->enum('type', ['POD', 'POS']);
            $table->enum('status', ['New', 'Accepted', 'In Delivery', 'Delivered', 'Rejected', 'Cancelled'])
                  ->default('New');
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('distributor_id')->nullable()->constrained('distributors');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            // For linking POS to POD
            $table->foreignId('parent_po_id')->nullable()->constrained('purchase_orders');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
