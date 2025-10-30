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
            $table->string('key')->unique(); // Llave única para cada configuración
            $table->text('value')->nullable(); // Valor de la configuración
            $table->string('type')->default('text'); // text, number, color, boolean, textarea, email, tel, url
            $table->string('group')->default('general'); // general, contact, theme, twilio, notifications, social
            $table->string('label')->nullable(); // Etiqueta para mostrar en el formulario
            $table->text('description')->nullable(); // Descripción del setting
            $table->integer('order')->default(0); // Orden de visualización
            $table->timestamps();

            $table->index('key');
            $table->index('group');
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
