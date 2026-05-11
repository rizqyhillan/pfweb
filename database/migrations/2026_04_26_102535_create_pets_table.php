<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('hewan', function (Blueprint $table) {
    $table->id();

    $table->foreignId('id_pemilik')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->string('nama_hewan');
    $table->string('jenis');
    $table->string('jenis_kelamin')->nullable();
    $table->date('tanggal_lahir')->nullable();

    $table->string('ras')->nullable();
    $table->string('umur')->nullable();

    $table->decimal('berat', 8, 2)->nullable();

    $table->text('catatan')->nullable();

    $table->string('foto')->nullable();

    $table->timestamps();
});
    }
    public function down(): void { Schema::dropIfExists('hewan'); }
};
