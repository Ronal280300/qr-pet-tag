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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique(); // Número de orden único
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('plan_id')->constrained()->onDelete('cascade');
            $table->integer('pets_quantity')->default(1); // Cantidad de mascotas
            $table->decimal('subtotal', 10, 2); // Precio base del plan
            $table->decimal('additional_pets_cost', 10, 2)->default(0); // Costo de mascotas adicionales
            $table->decimal('total', 10, 2); // Total a pagar
            $table->enum('status', ['pending', 'payment_uploaded', 'verified', 'rejected', 'completed', 'expired'])->default('pending');
            $table->string('payment_proof')->nullable(); // Ruta del comprobante de pago
            $table->timestamp('payment_uploaded_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->text('admin_notes')->nullable(); // Notas del administrador
            $table->timestamp('expires_at')->nullable(); // Fecha de expiración (para suscripciones)
            $table->boolean('auto_renew')->default(false); // Renovación automática
            $table->timestamps();

            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
