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
        Schema::create('groomings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_hewan')->constrained('hewan')->cascadeOnDelete();
            $table->foreignId('id_paket')->constrained('package_types')->cascadeOnDelete();
            $table->date('tanggal_grooming');
            $table->string('status')->default('pending'); // pending, aktif, selesai, batal
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->text('catatan_grooming')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groomings');
    }
};
