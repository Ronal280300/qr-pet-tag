<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar nuevos campos
        Schema::table('pets', function (Blueprint $table) {
            $table->integer('age_years')->nullable()->after('age');
            $table->integer('age_months')->nullable()->after('age_years');
        });

        // 2. Migrar datos existentes
        DB::table('pets')->whereNotNull('age')->get()->each(function ($pet) {
            $age = $pet->age;
            $ageUnit = $pet->age_unit ?? 'years';

            if ($ageUnit === 'months') {
                // Si está en meses, convertir a años y meses
                $years = intdiv($age, 12);
                $months = $age % 12;

                DB::table('pets')
                    ->where('id', $pet->id)
                    ->update([
                        'age_years' => $years,
                        'age_months' => $months
                    ]);
            } else {
                // Si está en años, solo años
                DB::table('pets')
                    ->where('id', $pet->id)
                    ->update([
                        'age_years' => $age,
                        'age_months' => 0
                    ]);
            }
        });

        // 3. Eliminar age_unit (ya no necesario)
        Schema::table('pets', function (Blueprint $table) {
            if (Schema::hasColumn('pets', 'age_unit')) {
                $table->dropColumn('age_unit');
            }
        });
    }

    public function down(): void
    {
        // Restaurar age_unit
        Schema::table('pets', function (Blueprint $table) {
            $table->string('age_unit', 10)->default('years')->after('age');
        });

        // Migrar de vuelta (convertir todo a meses si hay ambos)
        DB::table('pets')->whereNotNull('age_years')->get()->each(function ($pet) {
            $totalMonths = ($pet->age_years * 12) + ($pet->age_months ?? 0);

            if ($totalMonths >= 12) {
                $years = intdiv($totalMonths, 12);
                DB::table('pets')
                    ->where('id', $pet->id)
                    ->update([
                        'age' => $years,
                        'age_unit' => 'years'
                    ]);
            } else {
                DB::table('pets')
                    ->where('id', $pet->id)
                    ->update([
                        'age' => $totalMonths,
                        'age_unit' => 'months'
                    ]);
            }
        });

        // Eliminar nuevos campos
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn(['age_years', 'age_months']);
        });
    }
};
