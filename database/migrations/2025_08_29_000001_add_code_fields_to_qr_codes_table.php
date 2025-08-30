<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            // code: puede ser null, pero recomendamos unique
            if (!Schema::hasColumn('qr_codes', 'code')) {
                $table->string('code', 32)->nullable()->unique()->after('slug');
            }

            // activation_code: puede ser null, indexado (no unique)
            if (!Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->string('activation_code', 32)->nullable()->index()->after('code');
            }
        });

        // (opcional) Asegurar slug único si no estuviera ya. No todos los motores permiten
        // detectar índice por Schema, así que lo hacemos con SQL condicional simple.
        // Si ya tienes unique en slug, esta sección será ignorada por el motor.
        try {
            DB::statement('CREATE UNIQUE INDEX qr_codes_slug_unique ON qr_codes (slug)');
        } catch (\Throwable $e) {
            // índice ya existe o el motor no lo permite — ignoramos
        }
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            if (Schema::hasColumn('qr_codes', 'activation_code')) {
                $table->dropColumn('activation_code');
            }
            if (Schema::hasColumn('qr_codes', 'code')) {
                // Si se creó un índice unique automáticamente, Laravel lo elimina con dropColumn
                $table->dropColumn('code');
            }
        });

        try {
            DB::statement('DROP INDEX qr_codes_slug_unique ON qr_codes');
        } catch (\Throwable $e) {
            // ignorar si no existe
        }
    }
};
