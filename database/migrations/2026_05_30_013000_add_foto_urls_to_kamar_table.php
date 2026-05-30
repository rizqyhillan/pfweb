<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            if (! Schema::hasColumn('kamar', 'foto_urls')) {
                $table->json('foto_urls')->nullable()->after('fasilitas');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kamar', function (Blueprint $table) {
            if (Schema::hasColumn('kamar', 'foto_urls')) {
                $table->dropColumn('foto_urls');
            }
        });
    }
};
