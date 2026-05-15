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
            $table->string('paket', 50)->default('basic');

            $table->integer('kapasitas')->default(1);
            $table->integer('terisi')->default(0);

            $table->decimal('harga_per_hari', 14, 2)->default(0);
            $table->text('fasilitas')->nullable();

            $table->enum('status', [
                'tersedia',
                'penuh',
                'maintenance',
                'tidak_aktif',
            ])->default('tersedia');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};