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
        Schema::create('email_campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nombre de la campaña
            $table->foreignId('email_template_id')->constrained('email_templates')->onDelete('cascade');
            $table->string('status')->default('draft'); // draft, scheduled, sending, sent, failed

            // Configuración de filtros
            $table->json('filter_config')->nullable(); // Configuración de filtros en JSON
            $table->string('filter_type')->nullable(); // all, no_scans, payment_due, custom

            // Parámetros de filtros específicos
            $table->integer('no_scans_days')->nullable(); // Días sin lecturas
            $table->integer('payment_due_days')->nullable(); // Días antes del vencimiento

            // Estadísticas
            $table->integer('total_recipients')->default(0); // Total de destinatarios
            $table->integer('sent_count')->default(0); // Enviados exitosos
            $table->integer('failed_count')->default(0); // Fallidos

            // Fechas
            $table->timestamp('scheduled_at')->nullable(); // Cuándo se programó el envío
            $table->timestamp('started_at')->nullable(); // Cuándo empezó a enviar
            $table->timestamp('completed_at')->nullable(); // Cuándo terminó

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Admin que creó
            $table->timestamps();

            $table->index('status');
            $table->index('filter_type');
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_campaigns');
    }
};
