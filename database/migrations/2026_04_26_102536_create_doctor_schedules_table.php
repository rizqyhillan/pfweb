<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jadwal_dokter', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dokter')->constrained('users')->onDelete('cascade');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->unsignedInteger('kuota')->default(10);
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('jadwal_dokter'); }
};
