<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hewan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pemilik')->constrained('users')->onDelete('cascade');
            $table->string('nama_hewan', 100);
            $table->string('jenis', 50);
            $table->string('ras', 100)->nullable();
            $table->string('umur', 30)->nullable();
            $table->decimal('berat', 5, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hewan'); }
};
