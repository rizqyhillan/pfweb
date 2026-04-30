<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penitipan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_hewan')->constrained('hewan');
            $table->foreignId('id_kamar')->constrained('kamar');
            $table->date('tanggal_masuk');
            $table->date('tanggal_rencana_keluar');
            $table->date('tanggal_keluar')->nullable();
            $table->text('catatan_titip')->nullable();
            $table->text('catatan_jemput')->nullable();
            $table->enum('status', ['pending', 'aktif', 'selesai', 'batal'])->default('pending');
            $table->decimal('total_biaya', 14, 2)->default(0.00);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('penitipan'); }
};
