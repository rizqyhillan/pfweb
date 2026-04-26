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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->enum('type', ['consultation', 'vaccination', 'grooming', 'surgery', 'boarding', 'other'])->default('other');
            $table->decimal('price', 12, 2)->default(0.00);
            $table->unsignedInteger('duration_minutes')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
