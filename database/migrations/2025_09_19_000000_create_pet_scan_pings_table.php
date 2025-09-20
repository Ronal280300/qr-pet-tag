<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pet_scan_pings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->cascadeOnDelete();
            $table->string('method', 8)->nullable();          // gps | ip | none
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->unsignedSmallInteger('accuracy')->nullable(); // metros (si GPS)
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('city', 64)->nullable();
            $table->string('region', 64)->nullable();
            $table->string('country', 64)->nullable();
            $table->string('address', 255)->nullable();       // opcional (reverse geocode)
            $table->boolean('notified')->default(false);      // si se envió correo al dueño
            $table->timestamps();

            $table->index(['pet_id', 'created_at']);
            $table->index(['pet_id', 'notified', 'created_at']);
            $table->index('ip');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pet_scan_pings');
    }
};
