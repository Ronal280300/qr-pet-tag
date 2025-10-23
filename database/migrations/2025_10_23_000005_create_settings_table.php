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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Clave única del setting
            $table->text('value')->nullable(); // Valor del setting (puede ser JSON)
            $table->string('type')->default('string'); // Tipo: string, integer, boolean, json
            $table->string('group')->default('general'); // Grupo de configuración
            $table->text('description')->nullable(); // Descripción del setting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
