<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo si la columna existe y aún no es única
        if (Schema::hasColumn('users', 'phone')) {
            // Antes de crear índice único, normaliza/limpia duplicados si los tuvieras.
            // Aquí asumimos que no tienes duplicados. Si los hay, resuélvelos manualmente.

            Schema::table('users', function (Blueprint $table) {
                $table->unique('phone', 'users_phone_unique');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropUnique('users_phone_unique');
            });
        }
    }
};
