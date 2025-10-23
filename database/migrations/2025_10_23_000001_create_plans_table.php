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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "Pago Único", "Suscripción 1 Mes"
            $table->enum('type', ['one_time', 'subscription']); // Tipo de plan
            $table->integer('duration_months')->nullable(); // null para one_time, 1, 3, 6 para subscription
            $table->integer('pets_included')->default(1); // Cantidad de mascotas incluidas
            $table->decimal('price', 10, 2); // Precio en colones
            $table->decimal('additional_pet_price', 10, 2); // Precio por mascota adicional
            $table->boolean('is_active')->default(true); // Si el plan está activo
            $table->text('description')->nullable(); // Descripción del plan
            $table->integer('sort_order')->default(0); // Orden de visualización
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
