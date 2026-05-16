<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('keranjang_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_keranjang')
                ->constrained('keranjang')
                ->cascadeOnDelete();

            $table->foreignId('id_barang')
                ->constrained('barang')
                ->cascadeOnDelete();

            $table->integer('jumlah')->default(1);
            $table->decimal('harga_satuan', 14, 2)->default(0);
            $table->decimal('subtotal', 14, 2)->default(0);

            $table->timestamps();

            $table->unique(['id_keranjang', 'id_barang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('keranjang_items');
    }
};