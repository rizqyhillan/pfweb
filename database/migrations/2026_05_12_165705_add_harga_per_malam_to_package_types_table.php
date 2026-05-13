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
        Schema::table('package_types', function (Blueprint $table) {
            $table->decimal('harga_per_malam', 12, 2)->default(0)->after('label');
        });

        $defaultPrices = [
            'basic' => 50000,
            'regular' => 100000,
            'premium' => 150000,
        ];

        foreach ($defaultPrices as $name => $price) {
            DB::table('package_types')
                ->where('name', $name)
                ->update(['harga_per_malam' => $price]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('package_types', function (Blueprint $table) {
            $table->dropColumn('harga_per_malam');
        });
    }
};
