<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('pets')) {
            throw new RuntimeException('La tabla pets no existe. Revisa migraciones.');
        }

        Schema::table('pets', function (Blueprint $table) {
            // Zona / barrio / distrito (sin dirección exacta)
            $table->string('zone', 120)->nullable()->after('breed');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('zone');
        });
    }
};