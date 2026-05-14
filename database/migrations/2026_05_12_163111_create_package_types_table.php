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
            $table->decimal('harga_per_malam', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->json('fasilitas')->nullable();
            $table->timestamps();
        });

        $existingPackages = DB::table('kamar')->distinct()->pluck('paket');

        foreach ($existingPackages as $package) {
            DB::table('package_types')->updateOrInsert(
                ['name' => $package],
                ['label' => ucfirst(str_replace(['-', '_'], ' ', $package)), 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $defaults = [
            'basic' => [
                'label' => 'Basic',
                'harga_per_malam' => 50000,
                'description' => 'Mandi + pengeringan bulu',
                'fasilitas' => json_encode(['Mandi dengan shampoo khusus', 'Pengeringan bulu', 'Penyisiran bulu']),
            ],
            'regular' => [
                'label' => 'Regular',
                'harga_per_malam' => 100000,
                'description' => 'Basic + potong kuku & telinga',
                'fasilitas' => json_encode(['Semua layanan Basic', 'Potong kuku', 'Bersihkan telinga', 'Parfum hewan']),
            ],
            'premium' => [
                'label' => 'Premium',
                'harga_per_malam' => 150000,
                'description' => 'Regular + styling & spa',
                'fasilitas' => json_encode(['Semua layanan Regular', 'Styling rambut', 'Spa & pijat relaksasi', 'Bandana/aksesoris']),
            ],
        ];

        foreach ($defaults as $name => $data) {
            DB::table('package_types')->updateOrInsert(
                ['name' => $name],
                array_merge($data, ['updated_at' => now(), 'created_at' => now()])
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
