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
        Schema::create('boardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets');
            $table->foreignId('room_id')->constrained('rooms');
            $table->date('check_in_date');
            $table->date('planned_check_out_date');
            $table->date('check_out_date')->nullable();
            $table->text('drop_off_notes')->nullable();
            $table->text('pick_up_notes')->nullable();
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->decimal('total_cost', 14, 2)->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('boardings');
    }
};
