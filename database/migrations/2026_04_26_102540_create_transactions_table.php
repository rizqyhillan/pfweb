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
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('cashier_id')->nullable()->constrained('users');
            $table->string('transaction_code', 30)->unique();
            $table->enum('type', ['service', 'product', 'mixed'])->default('mixed');
            $table->decimal('total', 14, 2)->default(0.00);
            $table->enum('payment_method', ['cash', 'transfer', 'ewallet'])->default('cash');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->dateTime('date')->useCurrent();
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
