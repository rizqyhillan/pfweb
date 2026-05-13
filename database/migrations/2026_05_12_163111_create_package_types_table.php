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
        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('label', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });

        $existingPackages = DB::table('kamar')->distinct()->pluck('paket');

        foreach ($existingPackages as $package) {
            DB::table('package_types')->updateOrInsert(
                ['name' => $package],
                ['label' => ucfirst(str_replace(['-', '_'], ' ', $package)), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $defaultPackages = [
            'basic' => 'Basic',
            'regular' => 'Regular',
            'premium' => 'Premium',
        ];

        foreach ($defaultPackages as $name => $label) {
            DB::table('package_types')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'updated_at' => now(), 'created_at' => now()]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('package_types');
    }
};
