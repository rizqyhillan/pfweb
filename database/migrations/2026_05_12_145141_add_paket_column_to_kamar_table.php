<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tambah kolom paket
        Schema::table('kamar', function (Blueprint $table) {
            $table->string('paket', 50)->default('basic')->after('nama_kamar');
        });

        // 2. Konversi data dari tipe ke paket
        DB::table('kamar')->where('tipe', 'kecil')->update(['paket' => 'basic']);
        DB::table('kamar')->where('tipe', 'sedang')->update(['paket' => 'regular']);
        DB::table('kamar')->where('tipe', 'besar')->update(['paket' => 'premium']);

        // 3. Hapus kolom tipe
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan kolom tipe dari paket
        Schema::table('kamar', function (Blueprint $table) {
            $table->enum('tipe', ['kecil', 'sedang', 'besar'])->default('sedang')->after('nama_kamar');
        });

        // Konversi data balik dari paket ke tipe
        DB::table('kamar')->where('paket', 'basic')->update(['tipe' => 'kecil']);
        DB::table('kamar')->where('paket', 'regular')->update(['tipe' => 'sedang']);
        DB::table('kamar')->where('paket', 'premium')->update(['tipe' => 'besar']);

        // Hapus kolom paket
        Schema::table('kamar', function (Blueprint $table) {
            $table->dropColumn('paket');
        });
    }
};
