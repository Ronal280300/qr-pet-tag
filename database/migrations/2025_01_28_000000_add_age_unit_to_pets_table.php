<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            // Agregar campo para distinguir entre años y meses
            // Valores posibles: 'years' o 'months'
            // Por defecto 'years' para compatibilidad con datos existentes
            $table->string('age_unit', 10)->default('years')->after('age');
        });
    }

    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropColumn('age_unit');
        });
    }
};
