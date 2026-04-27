<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang', 150);
            $table->string('kategori', 100)->nullable();
            $table->decimal('harga', 12, 2)->default(0.00);
            $table->unsignedInteger('stok')->default(0);
            $table->string('satuan', 20)->default('pcs');
            $table->text('deskripsi')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('barang'); }
};
