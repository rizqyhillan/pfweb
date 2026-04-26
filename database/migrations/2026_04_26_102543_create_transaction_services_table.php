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
        Schema::create('transaction_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('transactions')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services');
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->decimal('subtotal', 14, 2)->storedAs('quantity * unit_price');
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_services');
    }
};
