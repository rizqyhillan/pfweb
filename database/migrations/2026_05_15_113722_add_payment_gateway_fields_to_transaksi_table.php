<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->string('payment_provider')->nullable()->after('status');
            $table->string('payment_channel')->nullable()->after('payment_provider');
            $table->string('payment_reference')->nullable()->after('payment_channel');
            $table->string('payment_token')->nullable()->after('payment_reference');
            $table->text('payment_redirect_url')->nullable()->after('payment_token');
            $table->string('payment_status')->default('pending')->after('payment_redirect_url');
            $table->timestamp('payment_expired_at')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_expired_at');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropColumn([
                'payment_provider',
                'payment_channel',
                'payment_reference',
                'payment_token',
                'payment_redirect_url',
                'payment_status',
                'payment_expired_at',
                'paid_at',
            ]);
        });
    }
};