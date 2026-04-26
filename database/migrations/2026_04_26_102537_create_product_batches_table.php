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
        Schema::create('product_batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null');
            $table->string('batch_number', 50)->nullable();
            $table->decimal('purchase_price', 12, 2)->default(0.00);
            $table->unsignedInteger('quantity_received')->default(0);
            $table->unsignedInteger('stock_remaining')->default(0);
            $table->date('received_date');
            $table->date('expired_date')->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_batches');
    }
};
