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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->decimal('sub_total', 10, 2); 
            $table->decimal('flat_discount', 5, 2)->default(0); 
            $table->decimal('tax_amount', 10, 2); 
            $table->decimal('total_amount', 10, 2); 
            $table->enum('payment_method', ['cash', 'credit', 'paypal']); // Payment method (Cash, Credit, PayPal)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
