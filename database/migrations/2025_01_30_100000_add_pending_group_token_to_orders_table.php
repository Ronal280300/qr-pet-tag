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
            // Agregar campo para vincular con las mascotas pendientes
            $table->string('pending_group_token')->nullable()->after('order_number')->index();
            // Agregar email pendiente para referencia
            $table->string('pending_email')->nullable()->after('pending_group_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['pending_group_token', 'pending_email']);
        });
    }
};
