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
        Schema::create('email_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la plantilla
            $table->string('subject'); // Asunto del email
            $table->text('description')->nullable(); // Descripción de la plantilla
            $table->longText('html_content'); // Contenido HTML del email
            $table->string('category')->default('general'); // Categoría: general, payment_reminder, update_data, etc.
            $table->boolean('is_active')->default(true); // Si está activa o no
            $table->timestamps();

            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_templates');
    }
};
