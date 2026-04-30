<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_hewan')->constrained('hewan')->onDelete('cascade');
            $table->foreignId('id_dokter')->nullable()->constrained('users')->onDelete('set null');
            $table->text('diagnosa')->nullable();
            $table->text('tindakan')->nullable();
            $table->text('resep')->nullable();
            $table->decimal('berat_saat_itu', 8, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->dateTime('tanggal')->useCurrent();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('rekam_medis'); }
};
