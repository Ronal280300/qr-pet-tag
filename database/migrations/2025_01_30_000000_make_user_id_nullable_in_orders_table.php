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
            // Eliminar la foreign key constraint existente
            $table->dropForeign(['user_id']);

            // Modificar la columna para que sea nullable
            $table->foreignId('user_id')->nullable()->change();

            // Re-crear la foreign key sin cascade delete (para que pueda ser null)
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Eliminar la foreign key
            $table->dropForeign(['user_id']);

            // Volver a hacer la columna NOT NULL
            $table->foreignId('user_id')->nullable(false)->change();

            // Re-crear la foreign key original con cascade
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
