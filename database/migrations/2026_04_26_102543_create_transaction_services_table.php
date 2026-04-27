<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi_layanan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('transaksi')->onDelete('cascade');
            $table->foreignId('id_layanan')->constrained('layanan');
            $table->unsignedInteger('jumlah')->default(1);
            $table->decimal('harga_satuan', 12, 2)->default(0.00);
            $table->decimal('subtotal', 14, 2)->default(0.00);
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('transaksi_layanan'); }
};
