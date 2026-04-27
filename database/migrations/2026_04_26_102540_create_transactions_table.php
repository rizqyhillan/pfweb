<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pelanggan')->constrained('users');
            $table->foreignId('id_kasir')->nullable()->constrained('users');
            $table->string('kode_transaksi', 30)->unique();
            $table->enum('jenis', ['layanan', 'barang', 'campuran'])->default('campuran');
            $table->decimal('subtotal', 14, 2)->default(0.00);
            $table->decimal('diskon', 14, 2)->default(0.00);
            $table->decimal('total', 14, 2)->default(0.00);
            $table->decimal('jumlah_bayar', 14, 2)->default(0.00);
            $table->decimal('kembalian', 14, 2)->default(0.00);
            $table->enum('metode_bayar', ['cash', 'transfer', 'ewallet'])->default('cash');
            $table->enum('status', ['pending', 'lunas', 'batal'])->default('pending');
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('transaksi'); }
};
