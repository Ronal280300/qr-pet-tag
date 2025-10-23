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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('current_plan_id')->nullable()->constrained('plans')->onDelete('set null');
            $table->foreignId('current_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->integer('pets_limit')->default(1); // Límite de mascotas según plan
            $table->timestamp('plan_started_at')->nullable();
            $table->timestamp('plan_expires_at')->nullable();
            $table->boolean('plan_is_active')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['current_plan_id']);
            $table->dropForeign(['current_order_id']);
            $table->dropColumn([
                'current_plan_id',
                'current_order_id',
                'pets_limit',
                'plan_started_at',
                'plan_expires_at',
                'plan_is_active'
            ]);
        });
    }
};
