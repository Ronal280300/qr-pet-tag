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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient'); // Email del destinatario
            $table->string('subject'); // Asunto del email
            $table->string('type')->nullable(); // Tipo de email: order_confirmation, payment_received, etc.
            $table->foreignId('related_order_id')->nullable()->constrained('orders')->onDelete('set null');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->text('error_message')->nullable(); // Mensaje de error si falló
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->index('sent_at');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
