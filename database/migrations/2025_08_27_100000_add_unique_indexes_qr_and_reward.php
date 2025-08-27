<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1 QR por mascota
        if (! Schema::hasColumn('qr_codes', 'pet_id')) {
            throw new RuntimeException('La tabla qr_codes no tiene pet_id. Revisa migraciones.');
        }
        Schema::table('qr_codes', function (Blueprint $table) {
            // Evita duplicidad de QR por mascota
            $table->unique('pet_id', 'qr_codes_pet_id_unique');
        });

        // 1 recompensa por mascota
        if (! Schema::hasColumn('rewards', 'pet_id')) {
            throw new RuntimeException('La tabla rewards no tiene pet_id. Revisa migraciones.');
        }
        Schema::table('rewards', function (Blueprint $table) {
            $table->unique('pet_id', 'rewards_pet_id_unique');
        });
    }

    public function down(): void
    {
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->dropUnique('qr_codes_pet_id_unique');
        });

        Schema::table('rewards', function (Blueprint $table) {
            $table->dropUnique('rewards_pet_id_unique');
        });
    }
};