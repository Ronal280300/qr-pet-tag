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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient'); // Número de teléfono con formato +506XXXXXXXX
            $table->text('message'); // Mensaje enviado
            $table->string('type')->nullable(); // Tipo de mensaje (payment_verified, payment_reminder, etc.)
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null'); // Orden relacionada
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Usuario relacionado
            $table->enum('status', ['sent', 'failed'])->default('sent'); // Estado del envío
            $table->string('twilio_sid')->nullable(); // SID de Twilio para tracking
            $table->text('error_message')->nullable(); // Mensaje de error si falló
            $table->timestamp('sent_at')->nullable(); // Cuándo se envió
            $table->timestamps();

            // Índices para búsquedas
            $table->index('recipient');
            $table->index('type');
            $table->index('status');
            $table->index('user_id');
            $table->index('order_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
