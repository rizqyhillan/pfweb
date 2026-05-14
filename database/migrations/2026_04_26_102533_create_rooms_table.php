<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kamar');
            $table->enum('tipe', ['kecil', 'sedang', 'besar'])->default('sedang');
            $table->decimal('harga_per_hari', 10, 2)->default(0);
            $table->integer('kapasitas')->default(1);
            $table->string('status', 50)->default('tersedia');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};
