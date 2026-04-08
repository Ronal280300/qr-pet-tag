<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('survey_responses', function (Blueprint $table) {
            $table->id();

            // Pregunta 1: ¿Tiene mascotas?
            $table->string('has_pets')->nullable();

            // Pregunta 2: ¿Qué tipo de mascota?
            $table->string('pet_type')->nullable();

            // Pregunta 3: ¿Cuál es su mayor preocupación?
            $table->string('main_concern')->nullable();

            // Pregunta 4: ¿Ha perdido una mascota antes?
            $table->string('lost_pet_before')->nullable();

            // Pregunta 5: ¿Compraría un tag QR para su mascota?
            $table->string('would_buy')->nullable();

            // Pregunta 6: ¿Cuánto pagaría? (rango de precio)
            $table->string('price_range')->nullable();

            // Pregunta 7: ¿Qué tan probable de 1-10?
            $table->unsignedTinyInteger('likelihood_score')->nullable();

            // Pregunta 8: Email (opcional para seguimiento)
            $table->string('email')->nullable();

            // Metadata
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referrer')->nullable();
            $table->string('source')->nullable(); // utm_source tracking

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('survey_responses');
    }
};
