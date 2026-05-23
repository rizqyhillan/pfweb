<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (!Schema::hasColumn('transaksi', 'payment_provider')) {
                $table->string('payment_provider')->nullable()->after('status');
            }
            if (!Schema::hasColumn('transaksi', 'payment_channel')) {
                $table->string('payment_channel')->nullable()->after('payment_provider');
            }
            if (!Schema::hasColumn('transaksi', 'payment_reference')) {
                $table->string('payment_reference')->nullable()->after('payment_channel');
            }
            if (!Schema::hasColumn('transaksi', 'payment_token')) {
                $table->string('payment_token')->nullable()->after('payment_reference');
            }
            if (!Schema::hasColumn('transaksi', 'payment_redirect_url')) {
                $table->text('payment_redirect_url')->nullable()->after('payment_token');
            }
            if (!Schema::hasColumn('transaksi', 'payment_status')) {
                $table->string('payment_status')->default('pending')->after('payment_redirect_url');
            }
            if (!Schema::hasColumn('transaksi', 'payment_type')) {
                $table->string('payment_type')->nullable()->after('payment_status');
            }
            if (!Schema::hasColumn('transaksi', 'payment_expired_at')) {
                $table->timestamp('payment_expired_at')->nullable()->after('payment_type');
            }
            if (!Schema::hasColumn('transaksi', 'paid_at')) {
                $table->timestamp('paid_at')->nullable()->after('payment_expired_at');
            }
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
                'payment_type',
                'payment_expired_at',
                'paid_at',
            ]);
        });
    }
};