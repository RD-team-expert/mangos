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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Links to the Item model
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade'); // Links to the Supplier model
            $table->integer('must_have')->default(0); // Quantity that must be in stock
            $table->integer('we_have')->default(0); // Quantity currently in stock
            $table->integer('to_order')->default(0); // Quantity to order
            $table->string('unit')->nullable(); // Unit of the item (e.g., 750 mL)
            $table->text('note')->nullable(); // Notes about the order
            $table->string('status')->default('pending'); // Order status (e.g., pending, ordered, received)
            $table->date('date_submitted')->nullable(); // Date the order was submitted
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
