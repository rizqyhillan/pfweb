<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang_batch', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_barang')->constrained('barang')->onDelete('cascade');
            $table->foreignId('id_supplier')->nullable()->constrained('supplier')->onDelete('set null');
            $table->string('no_batch', 50)->nullable();
            $table->decimal('harga_beli', 12, 2)->default(0.00);
            $table->unsignedInteger('jumlah_masuk')->default(0);
            $table->unsignedInteger('sisa_stok')->default(0);
            $table->date('tanggal_masuk');
            $table->date('tanggal_expired')->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barang_batch'); }
};
