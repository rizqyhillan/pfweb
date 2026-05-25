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
        Schema::table('groomings', function (Blueprint $table) {
            $table->foreignId('id_transaksi')
                ->nullable()
                ->after('id_paket')
                ->constrained('transaksi')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groomings', function (Blueprint $table) {
            $table->dropForeign(['id_transaksi']);
            $table->dropColumn('id_transaksi');
        });
    }
};
