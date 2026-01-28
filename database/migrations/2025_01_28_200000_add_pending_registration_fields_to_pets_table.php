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
        Schema::table('pets', function (Blueprint $table) {
            // Campos para sistema de registro pendiente (cuando admin crea mascota para cliente no registrado)
            $table->string('pending_email')->nullable()->after('user_id'); // Email al que se envió la invitación
            $table->string('pending_token')->nullable()->unique()->after('pending_email'); // Token único para el link
            $table->unsignedBigInteger('pending_plan_id')->nullable()->after('pending_token'); // Plan seleccionado por el admin
            $table->boolean('is_pending_registration')->default(false)->after('pending_plan_id'); // Flag para identificar mascotas pendientes
            $table->timestamp('pending_sent_at')->nullable()->after('is_pending_registration'); // Cuándo se envió el email
            $table->timestamp('pending_completed_at')->nullable()->after('pending_sent_at'); // Cuándo se completó el registro

            // Foreign key para el plan
            $table->foreign('pending_plan_id')->references('id')->on('plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pets', function (Blueprint $table) {
            $table->dropForeign(['pending_plan_id']);
            $table->dropColumn([
                'pending_email',
                'pending_token',
                'pending_plan_id',
                'is_pending_registration',
                'pending_sent_at',
                'pending_completed_at',
            ]);
        });
    }
};
