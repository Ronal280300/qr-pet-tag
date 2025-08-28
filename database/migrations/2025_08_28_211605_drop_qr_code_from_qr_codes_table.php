<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Quitar columna obsoleta
        if (Schema::hasColumn('qr_codes', 'qr_code')) {
            Schema::table('qr_codes', function (Blueprint $table) {
                $table->dropColumn('qr_code');
            });
        }

        // 2) (Opcional) asegurar que image sea NULLable sin requerir doctrine/dbal
        //    Solo si tu columna image NO era nullable. Si ya es nullable, este bloque no hace daño.
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE `qr_codes` MODIFY `image` VARCHAR(255) NULL");
        }
    }

    public function down(): void
    {
        // Volver a crear la columna si se revierte la migración
        Schema::table('qr_codes', function (Blueprint $table) {
            $table->text('qr_code')->nullable(); // la reponemos como nullable para evitar el problema al revertir
        });

        // (Opcional) volver image a NOT NULL si así estaba antes
        // DB::statement("ALTER TABLE `qr_codes` MODIFY `image` VARCHAR(255) NOT NULL");
    }
};
