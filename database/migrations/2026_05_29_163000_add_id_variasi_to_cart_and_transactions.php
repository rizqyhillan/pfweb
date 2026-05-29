<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('keranjang_items', function (Blueprint $table) {
            // Drop old unique constraint
            $table->dropUnique(['id_keranjang', 'id_barang']);
            
            // Add id_variasi column
            $table->foreignId('id_variasi')
                ->nullable()
                ->after('id_barang')
                ->constrained('product_variations')
                ->nullOnDelete();
        });

        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->foreignId('id_variasi')
                ->nullable()
                ->after('id_barang')
                ->constrained('product_variations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('keranjang_items', function (Blueprint $table) {
            $table->dropForeign(['id_variasi']);
            $table->dropColumn('id_variasi');
            $table->unique(['id_keranjang', 'id_barang']);
        });

        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropForeign(['id_variasi']);
            $table->dropColumn('id_variasi');
        });
    }
};
