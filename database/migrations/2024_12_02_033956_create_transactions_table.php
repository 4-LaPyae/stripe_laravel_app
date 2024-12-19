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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('customer_name', 50);
            $table->string('customer_email', 50);
            $table->string('item_name', 255);
            $table->string('item_number', 50);
            $table->decimal('item_price', 10, 2); // Use `decimal` for precise currency storage
            $table->string('item_price_currency', 10);
            $table->decimal('paid_amount', 10, 2); // Use `decimal` for precise currency storage
            $table->string('paid_amount_currency', 10);
            $table->string('txn_id', 50);
            $table->string('payment_status', 25);
            $table->string('stripe_checkout_session_id', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
