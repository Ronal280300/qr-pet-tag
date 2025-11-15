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
        Schema::table('settings', function (Blueprint $table) {
            // Verificar si las columnas no existen antes de agregarlas
            if (!Schema::hasColumn('settings', 'label')) {
                $table->string('label')->nullable()->after('group');
            }

            if (!Schema::hasColumn('settings', 'order')) {
                $table->integer('order')->default(0)->after('description');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (Schema::hasColumn('settings', 'label')) {
                $table->dropColumn('label');
            }

            if (Schema::hasColumn('settings', 'order')) {
                $table->dropColumn('order');
            }
        });
    }
};
