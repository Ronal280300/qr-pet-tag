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
        Schema::table('pets', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->after('user_id');
            $table->boolean('pending_activation')->default(false)->after('order_id');

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('set null');

            $table->index('order_id');
            $table->index(['pending_activation', 'order_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropIndex(['pets_order_id_index']);
            $table->dropIndex(['pets_pending_activation_order_id_index']);
            $table->dropColumn(['order_id', 'pending_activation']);
        });
    }
};
