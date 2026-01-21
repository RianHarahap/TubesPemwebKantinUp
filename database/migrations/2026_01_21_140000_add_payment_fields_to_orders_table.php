<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Tambah kolom untuk payment QRIS
            $table->enum('payment_status', ['pending', 'paid', 'expired'])->default('pending')->after('status');
            $table->text('qris_code')->nullable()->after('payment_status'); // untuk simpan data QRIS
            $table->timestamp('payment_expired_at')->nullable()->after('qris_code'); // QRIS expire dalam 5 menit
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'qris_code', 'payment_expired_at']);
        });
    }
};
