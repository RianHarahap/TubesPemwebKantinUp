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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('menu_id')->nullable()->constrained()->onDelete('set null')->after('vendor_id');
            $table->string('menu_name')->nullable()->after('menu_id');
            $table->integer('jumlah')->default(1)->after('menu_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['menu_id']);
            $table->dropColumn(['menu_id', 'menu_name', 'jumlah']);
        });
    }
};
