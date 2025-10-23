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
        Schema::create('admin_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Tipo de notificación: new_order, payment_uploaded, etc.
            $table->string('title'); // Título de la notificación
            $table->text('message'); // Mensaje
            $table->string('icon')->nullable(); // Icono (clase de FontAwesome o similar)
            $table->string('url')->nullable(); // URL a donde redirigir al hacer click
            $table->foreignId('related_order_id')->nullable()->constrained('orders')->onDelete('cascade');
            $table->foreignId('related_user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['is_read', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_notifications');
    }
};
