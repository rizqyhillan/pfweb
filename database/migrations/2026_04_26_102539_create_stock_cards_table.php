<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kartu_stok', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade');
            $table->foreignId('id_batch')->nullable()->constrained('barang_batch')->onDelete('set null');
            $table->dateTime('tanggal')->useCurrent();
            $table->enum('jenis_mutasi', ['masuk', 'keluar', 'adjustment', 'retur', 'expired']);
            $table->integer('jumlah');
            $table->unsignedInteger('saldo');
            $table->decimal('harga_satuan', 12, 2)->default(0.00);
            $table->string('referensi', 100)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kartu_stok'); }
};
