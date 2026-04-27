<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_layanan', 150);
            $table->enum('jenis_layanan', ['konsultasi', 'vaksinasi', 'grooming', 'operasi', 'penitipan', 'lainnya'])->default('lainnya');
            $table->decimal('harga', 12, 2)->default(0.00);
            $table->unsignedInteger('durasi_menit')->nullable();
            $table->text('deskripsi')->nullable();
            $table->foreignId('id_dokter')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('layanan'); }
};
