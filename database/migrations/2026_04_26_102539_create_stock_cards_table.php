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
        Schema::create('stock_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained('product_batches')->onDelete('cascade');
            $table->dateTime('date')->useCurrent();
            $table->enum('mutation_type', ['in', 'out', 'adjustment', 'return', 'expired']);
            $table->integer('quantity');
            $table->unsignedInteger('balance');
            $table->decimal('unit_price', 12, 2)->default(0.00);
            $table->string('reference', 100)->nullable();
            $table->string('notes', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_cards');
    }
};
