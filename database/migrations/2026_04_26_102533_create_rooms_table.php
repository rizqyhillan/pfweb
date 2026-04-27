<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kamar', 50);
            $table->enum('tipe', ['kecil', 'sedang', 'besar'])->default('sedang');
            $table->decimal('harga_per_hari', 12, 2)->default(0.00);
            $table->unsignedInteger('kapasitas')->default(1);
            $table->enum('status', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('kamar'); }
};
