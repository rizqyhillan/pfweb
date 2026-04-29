<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Step 1: Expand enum to include ALL values (old + new)
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','dokter','pemilik','kasir','doctor','karyawan') DEFAULT 'admin'");

        // Step 2: Rename old role values to new ones
        DB::table('users')->where('role', 'pemilik')->update(['role' => 'admin']);
        DB::table('users')->where('role', 'dokter')->update(['role' => 'doctor']);
        DB::table('users')->where('role', 'kasir')->update(['role' => 'karyawan']);

        // Step 3: Shrink enum to only new values
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','doctor','karyawan') DEFAULT 'admin'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','dokter','pemilik','kasir','doctor','karyawan') DEFAULT 'pemilik'");

        DB::table('users')->where('role', 'admin')->update(['role' => 'pemilik']);
        DB::table('users')->where('role', 'doctor')->update(['role' => 'dokter']);
        DB::table('users')->where('role', 'karyawan')->update(['role' => 'kasir']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','dokter','pemilik','kasir') DEFAULT 'pemilik'");
    }
};
