<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('keranjang_items', function (Blueprint $table) {
            // Drop foreign key constraints first so MySQL allows dropping the index
            $table->dropForeign(['id_keranjang']);
            $table->dropForeign(['id_barang']);

            // Now drop the old unique constraint
            $table->dropUnique(['id_keranjang', 'id_barang']);
            
            // Recreate original foreign keys (which creates indices automatically)
            $table->foreign('id_keranjang')
                ->references('id')
                ->on('keranjang')
                ->cascadeOnDelete();

            $table->foreign('id_barang')
                ->references('id')
                ->on('barang')
                ->cascadeOnDelete();

            // Add id_variasi column
            $table->foreignId('id_variasi')
                ->nullable()
                ->after('id_barang')
                ->constrained('product_variations')
                ->nullOnDelete();

            // Add new unique index
            $table->unique(['id_keranjang', 'id_barang', 'id_variasi'], 'keranjang_items_unique');
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
            $table->dropUnique('keranjang_items_unique');
            $table->dropForeign(['id_variasi']);
            $table->dropColumn('id_variasi');

            // Drop foreign keys to recreate unique index
            $table->dropForeign(['id_keranjang']);
            $table->dropForeign(['id_barang']);

            $table->unique(['id_keranjang', 'id_barang']);

            $table->foreign('id_keranjang')
                ->references('id')
                ->on('keranjang')
                ->cascadeOnDelete();

            $table->foreign('id_barang')
                ->references('id')
                ->on('barang')
                ->cascadeOnDelete();
        });

        Schema::table('transaksi_barang', function (Blueprint $table) {
            $table->dropForeign(['id_variasi']);
            $table->dropColumn('id_variasi');
        });
    }
};
