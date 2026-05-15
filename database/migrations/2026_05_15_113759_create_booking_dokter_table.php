<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_dokter', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_hewan')
                ->constrained('hewan')
                ->cascadeOnDelete();

            $table->foreignId('id_dokter')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('id_layanan')
                ->nullable()
                ->constrained('layanan')
                ->nullOnDelete();

            $table->foreignId('id_jadwal')
                ->nullable()
                ->constrained('jadwal_dokter')
                ->nullOnDelete();

            $table->foreignId('id_transaksi')
                ->nullable()
                ->constrained('transaksi')
                ->nullOnDelete();

            $table->date('tanggal_booking');
            $table->time('jam_booking');

            $table->text('keluhan')->nullable();
            $table->text('catatan_dokter')->nullable();

            $table->enum('status', [
                'pending',
                'dikonfirmasi',
                'selesai',
                'batal'
            ])->default('pending');

            $table->decimal('total_biaya', 14, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_dokter');
    }
};