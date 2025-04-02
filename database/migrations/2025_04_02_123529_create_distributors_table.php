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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('business_name');
            $table->text('address');
            $table->string('country');
            $table->string('vat_number');
            // Sales Contact
            $table->string('sales_contact_name');
            $table->string('sales_contact_email');
            $table->string('sales_contact_phone');
            // Logistics Contact
            $table->string('logistics_contact_name');
            $table->string('logistics_contact_email');
            $table->string('logistics_contact_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};
